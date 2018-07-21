<?php

  // UI Routers: 浏览器可访问UI

  build::get('story/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/ui/story@index.php';
  }); // 故事主页

  build::get('story/admin/new_story', function () {
      require user::dir(-1).'/service/ui/admin@new_story.php';
  }); // 新建主题

  build::get('story/admin/unactivated/card', function () {
      require user::dir(-1).'/service/ui/admin@unactivated_card.php';
  }); // 所有未激活投稿

  // 未实现
  /*
  build::get('story/all', function () {
      require user::dir(-1).'/service/ui/story@all.php';
  }); // 所有主题（已激活）

  build::get('story/admin/unactivated/story', function () {
      require user::dir(-1).'/service/ui/admin@unactivated_story.php';
  }); // 所有未激活主题

  build::get('story/admin/unactivated/comment', function () {
      require user::dir(-1).'/service/ui/admin@unactivated_comment.php';
  }); // 所有未激活留言

  build::get('story/admin/invite', function () {
      require user::dir(-1).'/service/ui/admin@invite.php';
  }); // 根据tag邀请用户
  */
