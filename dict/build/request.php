<?php

  // Request Routers: 异步获取UI

  build::get('dict/request/menu/qr_story/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/request/menu@qr_story.php';
  }); //菜单，获取story二维码

  build::get('dict/request/like/[card_id]/[action]', function ($card_id, $action) {
      require user::dir(-1).'/service/request/like.php';
  }); // 点赞
