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

  function sub_time($time_a, $time_b)
  {
      $datetime1 = new DateTime($time_a);
      $datetime2 = new DateTime($time_b);
      $interval = $datetime1->diff($datetime2);
      return $interval->format('%a');  // 计算还有多少天
  } // 两个时间戳相减，返回天数


  // 处理请求/访问数据库
  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $access_token = $wx->access_token();
  $jsapi = $wx->jsapi($access_token);
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

  // 计算是否冻住了
  $freeze_ddl = date('Ymd', strtotime($story_data['create_time']) + $story_data['freeze_time']*24*60*60);
  $reopen_ddl = date('Ymd', strtotime($story_data['create_time']) + $story_data['reopen_time']*24*60*60);
  $current_time = date('Ymd');

  view::global([
    'story_id' => $story_id,
    'url' => user::url(),
    'title' => $story_data['title'],
    'description' => $story_data['description'],
    'color' => $story_data['color'],
    'prefix' => $story_data['prefix'],
    'freeze_time' => $story_data['freeze_time'],
    'reopen_time' => $story_data['reopen_time'],
    'reopen_description' => $story_data['reopen_description'],
    'create_time' => $story_data['create_time'],
    'count_cards' => $story_data['count_cards'],
    'logined' => view::if($openid!='' && ($current_time<=$freeze_ddl || $current_time>=$reopen_ddl)),
    'not_logined' => view::if($openid=='' && ($current_time<=$freeze_ddl || $current_time>=$reopen_ddl)),
    'freeze' => view::if($current_time>$freeze_ddl && $current_time<$reopen_ddl),
    'js_appid' => $jsapi['appid'],
    'js_time' => $jsapi['timestamp'],
    'js_nonce' => $jsapi['noncestr'],
    'js_sign' => $jsapi['signature'],
  ]); // 设置全局渲染变量

  $card_view = '';
  if ($current_time>$freeze_ddl && $current_time<$reopen_ddl) {
      // 如果主题被冻住
      $to_day = sub_time($reopen_ddl, $current_time);  // 计算还有多少天
      $counter_text = '距离解冻还有'.$to_day.'天';
      $card_view .= view::render('story/freeze_card.html', ['to_day'=>$to_day]);
  } else {
      if ($current_time<$freeze_ddl) {
          $to_day = sub_time($freeze_ddl, $current_time);  // 计算还有多少天
          $counter_text = '还有'.$to_day.'天冻住';
      } else {
          $counter_text = '已解冻';
      }
      // 渲染用户的待审核投稿
      if ($openid!='') {
          if ($waiting_cards = sql::select('cards')->where('openid=? and activate=0', [$openid])->order('weight')->by('desc')->fetch()) {
              foreach ($waiting_cards as $v) {
                  $card_view .= view::render('story/waiting_card.html', [
                    'is_img' => view::if($v['img']!=''),
                    'img' => $v['img'],
                    'text' => is::empty($v['content']) ? '&nbsp;' : $v['content'].'<br>&nbsp;'
                  ]);
              }
          }
      }

      // 渲染故事卡片
      if ($story_data['activate']==0 && is::in($openid, $GLOBALS['admin'])) {
          // 如果未激活且访问者是体制内人
          $card_view .= view::render('story/activate_card.html');
      } else {
          if ($story_data['count_cards']==0) {
              // 如果没人投稿
              $card_view .= view::render('story/empty_card.html');
          } else {
              // 如果有人投稿，渲染卡片
              if ($card_data = sql::select('cards')->where('story_id = ? and activate = 1', [$story_id])->order('weight')->by('desc')->fetch()) {
                  foreach ($card_data as $v) {
                      $if_liked = sql::select('likes')->where('card_id=? and ip=? and agent=?', [$v['id'],user::ip(),user::agent()])->limit(1)->fetch();
                      $card_view .= view::render('story/card.html', [
                        'card_id' => $v['id'],
                        'nick' => $v['nick'],
                        'is_img' => view::if($v['img']!=''),
                        'is_like' => view::if($if_liked),
                        'not_like' => view::if(!$if_liked),
                        'likes' => sql::select('likes')->where('card_id=?', [$v['id']])->count(),
                        'img' => $v['img'],
                        'text' => is::empty($v['content']) ? '&nbsp;' : $v['content'],
                      ]);
                  }
              } // 获取卡片数据
          }
      }
  }

  $resource_url = user::url().'/view/file/';

  echo view::render('story/main.html', [
    'style' => view::style([
      $resource_url.'style/icon.css',
      $resource_url.'style/main.css',
      $resource_url.'style/form.css'
    ]),
    'script' => view::script([
      'https://res.wx.qq.com/open/js/jweixin-1.2.0.js',
      'https://libs.baidu.com/jquery/1.9.1/jquery.min.js',
      user::url().'/view/file/script/main.js'
    ]),
    'bottom_script' => view::script([
      user::url().'/view/file/script/form.js',
      user::url().'/view/file/script/bottom_main.js'
    ]),
    'counter_text' => $counter_text,
    'card' => $card_view
  ]); // 输出渲染主题
