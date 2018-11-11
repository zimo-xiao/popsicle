<?php

  // UI Routers: 浏览器可访问UI

  build::get('dict/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/ui/story@index.php';
  }); // 故事主页

  build::get('dict/admin/new_story', function () {
      require user::dir(-1).'/service/ui/admin@new_story.php';
  }); // 新建主题

  build::get('dict/admin/unactivated/card', function () {
      require user::dir(-1).'/service/ui/admin@unactivated_card.php';
  }); // 所有未激活投稿
