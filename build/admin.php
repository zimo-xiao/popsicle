<?php

  // Admin Routers: 后台操作

  build::get('story/admin/a/approve/[card_id]', function ($card_id) {
      require user::dir(-1).'/service/admin/approve.php';
  }); // 批准card

  build::get('story/admin/a/activate/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/admin/activate.php';
  }); // 激活story

  build::get('story/admin/a/create', function () {
      require user::dir(-1).'/service/admin/create.php';
  }); // 生成story

  // 未实现
  /*
  build::get('story/admin/a/invite/[tag]', function ($tag) {
      require user::dir(-1).'/service/admin/invite.php';
  }); // 邀请一个tag的user
  */
