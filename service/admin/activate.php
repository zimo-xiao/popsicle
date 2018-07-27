<?php

/*
|--------------------------------------------------------------------------
| 激活主题
|--------------------------------------------------------------------------
|
| 输入
| - $story_id
|
|
*/

  sql::update('stories')->this([
    'create_time' => date('Ymd'),
    'activate' => 1
  ])->where('id=?', [$story_id])->execute();

  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $access_token = $wx->access_token();
  $value = 'story_'.$story_id;
  $service = 'sid_'.$story_id;
  require_once user::dir().'/service/plug/wechat/qr.php';

  js::alert('激活成功');
  jump::to(user::url().'/story/'.$story_id);
