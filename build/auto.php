<?php

  // Auto Routers: 自动定时操作

  build::get('story/auto/delete', function () {
      require user::dir(-1).'/service/auto/delete.php';
  }); // 自动删除所有垃圾

  build::get('story/auto/user', function () {
      require user::dir(-1).'/service/auto/user.php';
  }); // 自动更新用户列表
