<?php
/*
|--------------------------------------------------------------------------
| 生成带参数的二维码
|--------------------------------------------------------------------------
|
| 输入：
| - $value：二维码所要带的参数
| - $wx：微信method
| - $access_token
| - $service：调用qr的服务id
|
| 输出：
| - $ticket：参数二维码ticket
|
|
|
*/

  $ticket = $wx->get_qr_ticket($access_token, $value);
  sql::insert('qrs')->this([
    'ticket' => $ticket,
    'service' => $service
  ]);
