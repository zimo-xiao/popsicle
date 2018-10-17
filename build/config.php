<?php

   header("Content-type: text/html; charset=utf-8"); //recommend to use UTF-8

   date_default_timezone_set('PRC'); //recommend to use UTC time format

   sql::config([

     'address' => 'localhost',

     'username' => 'root',

     'password' => 'password',

     'database' => 'db'

   ]); //sql config


   $GLOBALS['admin'] = [
     'openid'
   ]; //管理人员

   $GLOBALS['open_code'] = [
     'service' => 'code'
   ]; //开放平台验证码


   $GLOBALS['wechat_config'] = [
     'appid'  => 'xxx',
     'secret' => 'xxx',
     'token'  => 'xxx'
   ];
