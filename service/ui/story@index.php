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

  // 处理请求/访问数据库
  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
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

  if (!$card_data = sql::select('cards')->where('story_id = ? and activate = 1', [$story_id])->order('weight')->by('desc')->fetch()) {
      $card_data = [];
  } // 获取卡片数据

  view::global([
    'story_id' => $story_id,
    'url' => user::url(),
    'title' => $story_data['title'],
    'description' => $story_data['description'],
    'top_img' => $story_data['img'],
    'prefix' => $story_data['prefix'],
    'if_logined' => view::if($openid!=''),
    'if_not_logined' => view::if($openid==='')
  ]); // 设置全局渲染变量

  $topic_view = view::render('story/topic.html', [
    'count_visits' => $story_data['count_visits']
  ]); // 渲染主题信息模块

  $resource_url = user::url().'/view/file/';

  echo view::render('story/main.html', [
    'style' => view::style([
      $resource_url.'style/icon.css',
      $resource_url.'style/topic_view.css'
    ]),
    'topic_view' => $topic_view
  ]); // 输出渲染主题
