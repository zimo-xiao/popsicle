<?php
/*
|--------------------------------------------------------------------------
| 生成带参数的二维码
|--------------------------------------------------------------------------
|
| 输入：
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

  if (!isset($return_data)) {
      $return_data = '';
  }
  $ticket = $wx->get_qr_ticket($access_token, $service);
  sql::insert('qrs')->this([$ticket, $service, $return_data]);
