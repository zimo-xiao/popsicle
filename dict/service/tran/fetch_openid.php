<?php

/*
|--------------------------------------------------------------------------
| 储存openID到session，并跳转至相关链接
|--------------------------------------------------------------------------
|
| 输入
| - $openid：用户openID
| - $url：特殊格式的跳转网址（a+b+c!d=1 => https://xy.zuggr.com/a/b/c?d=1）
|
|
*/

  session_start();
  $_SESSION['openid'] = $openid;
  $tran = user::url().'/';
  foreach (str::split('+', $url) as $value) {
      $tran .= $value.'/';
  }
  $tran = str::replace('!', '?', $tran);
  jump::to(rtrim($tran, '/'));
