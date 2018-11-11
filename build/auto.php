<?php

  // Auto Routers: 自动定时操作

  build::get('story/auto/delete', function () {
      require user::dir(-1).'/service/auto/delete.php';
  }); // 自动删除所有垃圾

  build::get('story/auto/noti', function () {
      require user::dir(-1).'/service/auto/noti.php';
  }); // 每晚7点自动推送

  build::get('story/auto/report', function () {
      require user::dir(-1).'/service/auto/report.php';
  }); // 自动生成报告
