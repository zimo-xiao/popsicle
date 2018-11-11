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

  $tags = explode('/', sql::select('stories')->where('id=?', [$story_id])->limit(1)->fetch()[0]['tags']);
  $return_data = json_encode([
    'tags' => $tags
  ]);

  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $access_token = $wx->access_token(user::dir(-2));
  $service = 'dict_'.$story_id;
  require_once user::dir().'/service/plug/wechat/qr.php';

  js::alert('激活成功');
  jump::to(user::url().'/dict/'.$story_id);
