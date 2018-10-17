<?php

  // Open Routers: 开放
  build::get('open/[code]/wechat/qr/generate/[service]/[value]', function ($code, $service, $value) {
      require user::dir(-1).'/service/open/base.php';
      require user::dir(-1).'/service/open/generate_qr.php';
  }); // 生成qr码

  build::get('open/[code]/wechat/qr/fetch/[service]', function ($code, $service) {
      require user::dir(-1).'/service/open/base.php';
      require user::dir(-1).'/service/open/fetch_qr.php';
  }); // 获取qr码
