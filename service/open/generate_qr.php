<?php

/*
|--------------------------------------------------------------------------
| 生成带参数的qr码
|--------------------------------------------------------------------------
|
| 输入
| - $value
| - $service
|
|
*/

  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $return_data = user::post('value');
  $value = user::post('id');
  $service = user::post('id');
  $access_token = $wx->access_token();
  foreach ($GLOBALS['open_code'] as $k => $v) {
      if ($v == $code) {
          $service = $k.'_'.$service;
          $value = $k.'_'.$value;
          require_once user::dir().'/service/plug/wechat/qr.php';
          echo $ticket;
          exit;
      }
  }
  echo '出现错误';
