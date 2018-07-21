<?php

  // WeChat Routers: 微信公众号

  build::post('api/wechat/zuggrcampus', function () {
      require user::dir(-1).'/service/wechat/index.php';
  }); // 微信公众号反馈主api

  build::get('story/wechat/menu', function () {
      require user::dir(-1).'/service/wechat/menu.php';
  }); // 更新公众号菜单栏
