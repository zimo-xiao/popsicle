<?php
/*
|--------------------------------------------------------------------------
| 处理前段请求&渲染主题主页面
|--------------------------------------------------------------------------
|
| 输入
| - $story_id
|
|
*/
  function str_split_unicode($str, $l = 0)
  {
      if ($l > 0) {
          $ret = array();
          $len = mb_strlen($str, "UTF-8");
          for ($i = 0; $i < $len; $i += $l) {
              $ret[] = mb_substr($str, $i, $l, "UTF-8");
          }
          return $ret;
      }
      return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
  } // 分割Unicode中文字符


  // 处理请求/访问数据库
  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $access_token = $wx->access_token(user::dir(-2));
  $jsapi = $wx->jsapi($access_token, user::dir(-2));
  session_start();
  $openid = isset($_SESSION['openid'])?$_SESSION['openid']:'';


  $story_data = sql::select('stories')->where('id=?', [$story_id])->limit(1)->fetch()[0];
  if (!$story_data) {
      jump::to(user::url().'/404');
      exit;
  } // 验证访问页面

  if (!sql::select('visits')->where('ip=? and agent=? and service=?', [user::ip(), user::agent(), 'story_'.$story_id])->limit(1)->fetch()) {
      sql::update('stories')->this(['count_visits' => $story_data['count_visits']+1])->where('id = ?', [$story_id])->execute();  // 更新主题计数器
      sql::insert('visits')->this([user::ip(), user::port(), user::agent(), 'story_'.$story_id]); // 添加访问log
  } //访问统计

  // 为标题的每个字加上田字格
  $framed_title = '';
  foreach (str_split_unicode($story_data['title']) as $title) {
      $framed_title .= '<div class="character_frame">'.$title.'</div>';
  }

  // 为拼音加上格子
  $pinyin = '';
  foreach (explode('/', $story_data['pinyin']) as $p) {
      $pinyin .= '<div class="pinyin_frame">'.$p.'</div>';
  }

  view::global([
    'story_id' => $story_id,
    'url' => user::url(),
    'title' => $story_data['title'],
    'pinyin' => str::replace('/', ' ', $story_data['pinyin']),
    'color' => $story_data['color'],
    'create_time' => $story_data['create_time'],
    'count_cards' => $story_data['count_cards'],
    'logined' => view::if($openid!=''),
    'not_logined' => view::if($openid==''),
    'js_appid' => $jsapi['appid'],
    'js_time' => $jsapi['timestamp'],
    'js_nonce' => $jsapi['noncestr'],
    'js_sign' => $jsapi['signature']
  ]); // 设置全局渲染变量

  $card_view = '';
  $final_photo = 'head.jpg';

  // 渲染用户的待审核投稿
  if ($openid!='') {
      if ($waiting_cards = sql::select('cards')->where('openid=? and story_id=? and activate=0', [$openid,$story_id])->order('weight')->by('desc')->fetch()) {
          foreach ($waiting_cards as $v) {
              $card_view .= view::render('story/waiting_card.html', [
                    'is_img' => view::if($v['img']!=''),
                    'is_content' => view::if($v['content']!=''),
                    'img' => $v['img'],
                    'text' => is::empty($v['content']) ? '&nbsp;' : $v['content'].'<br>&nbsp;'
                  ]);
          }
      }
  }

  // 渲染故事卡片
  if ($openid!='') {
      if ($story_data['activate']==0 && $openid!='') {
          // 如果未激活且访问者是体制内人
          if (is::in($openid, $GLOBALS['admin'])) {
              $card_view .= view::render('story/activate_card.html');
          }
      }
  }

  // 如果激活，渲染投稿
  if ($story_data['count_cards']==0) {
      // 如果没人投稿
      $card_view .= view::render('story/empty_card.html');
  } else {
      // 如果有人投稿，渲染卡片
      if ($card_data = sql::select('cards')->where('story_id = ? and activate = 1', [$story_id])->order('weight')->by('desc')->fetch()) {
          foreach ($card_data as $v) {
              if ($v['img']!='') {
                  $final_photo = $v['img'];
              }
              $if_liked = sql::select('likes')->where('card_id=? and ip=? and agent=?', [$v['id'],user::ip(),user::agent()])->limit(1)->fetch();
              $card_view .= view::render('story/card.html', [
                    'card_id' => $v['id'],
                    'nick' => $v['nick'],
                    'is_img' => view::if($v['img']!=''),
                    'is_content' => view::if($v['content']!=''),
                    'is_like' => view::if($if_liked),
                    'not_like' => view::if(!$if_liked),
                    'likes' => sql::select('likes')->where('card_id=?', [$v['id']])->count(),
                    'img' => $v['img'],
                    'text' => is::empty($v['content']) ? '&nbsp;' : $v['content'],
                  ]);
          }
      } // 获取卡片数据
  }

  $resource_url = user::url().'/dict/view/file/';

  echo view::render('story/main.html', [
    'style' => view::style([
      $resource_url.'style/icon.css',
      $resource_url.'style/main.css',
      $resource_url.'style/form.css'
    ]),
    'script' => view::script([
      'https://res.wx.qq.com/open/js/jweixin-1.2.0.js',
      'https://libs.baidu.com/jquery/1.9.1/jquery.min.js',
      $resource_url.'script/exif.min.js',
      $resource_url.'script/processImg.min.js',
      $resource_url.'script/lazy.js',
      $resource_url.'script/main.js'
    ]),
    'bottom_script' => view::script([
      $resource_url.'script/form.js',
      $resource_url.'script/bottom_main.js'
    ]),
    'card' => $card_view,
    'final_photo' => $final_photo,
    'framed_title' => $framed_title,
    'framed_pinyin' => $pinyin
  ]); // 输出渲染主题
