<?php

  // Request Routers: 异步获取UI

  build::get('story/request/menu/qr_story/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/request/menu@qr_story.php';
  }); //菜单，获取story二维码


  // 未实现
  /*
  build::get('story/request/comment/[card_id]', function ($card_id) {
      require user::dir(-1).'/service/request/comment.php';
  }); // 获取comment列表

  build::get('story/request/menu/write_comment/[card_id]', function ($card_id) {
      require user::dir(-1).'/service/request/menu@write_comment.php';
  }); // 菜单，获取留言输入框

  build::get('story/request/menu/qr_comment/[card_id]', function ($card_id) {
      require user::dir(-1).'/service/request/menu@qr_comment.php';
  }); //菜单，获取给用户留言的二维码

  build::get('story/request/menu/get_comment/[comment_id]', function ($comment_id) {
      require user::dir(-1).'/service/request/menu@get_comment.php';
  }); //菜单，获取来信留言
  */
