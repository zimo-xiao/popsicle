<?php
/*
|--------------------------------------------------------------------------
| 自动更新用户列表
|--------------------------------------------------------------------------
|
| 向微信请求所有用户openid，并更新个人信息，插入数据库
|
|
*/

  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $at = $wx->access_token();
  $out = $wx->get_all_user_id($at);
  foreach ($out as $openid) {
      if (!sql::select('users')->where('openid=?', [$openid])->limit(1)->fetch()) {
          $user = $wx->get_user_info($at, $openid);
          $headimg = $user->headimgurl;
          $sex = $user->sex;
          $name = $user->nickname;
          sql::insert('users')->this([$openid,$name,$sex,$headimg]);
      }
  }
