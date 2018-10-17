<?php

  // Open Routers: 开放
  build::post('open/[code]/wechat/qr', function ($code) {
      require user::dir(-1).'/service/open/base.php';
      require user::dir(-1).'/service/open/generate_qr.php';
  }); // 生成qr码

  build::get('open/[code]/wechat/qr/[service]', function ($code, $service) {
      require user::dir(-1).'/service/open/base.php';
      require user::dir(-1).'/service/open/fetch_qr.php';
  }); // 获取qr码
