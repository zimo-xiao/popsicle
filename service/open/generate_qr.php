<?php

/*
|--------------------------------------------------------------------------
| 生成带参数的qr码
|--------------------------------------------------------------------------
|
| 输入
| - id
| - value
|
|
*/

  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $return_data = user::post('value');
  $service = user::post('id');
  $access_token = $wx->access_token();
  foreach ($GLOBALS['open_code'] as $k => $v) {
      if ($v == $code) {
          $service = $k.'_'.$service;
          require_once user::dir().'/service/plug/wechat/qr.php';
          echo $ticket;
          exit;
      }
  }
  echo '出现错误';
