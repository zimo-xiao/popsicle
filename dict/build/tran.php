<?php

  // Tran Routers: 跳转操作

  build::post('dict/tran/new_story/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/tran/upload_card.php';
  }); // 上传投稿
