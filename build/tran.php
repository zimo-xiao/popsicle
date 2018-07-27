<?php

  // Tran Routers: 跳转操作

  build::post('story/tran/new_story/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/tran/upload_card.php';
  }); // 上传投稿

  build::get('story/tran/delete_card/[story_id]/[card_id]', function ($story_id, $card_id) {
      require user::dir(-1).'/service/tran/delete_card.php';
  }); // 删除故事

  build::get('story/tran/fetch_openid/[openid]/[url]', function ($openid, $url) {
      require user::dir(-1).'/service/tran/fetch_openid.php';
  }); // 微信跳转登录，获取用户openid

  // 未实现
  /*
  build::post('story/tran/new_story/[story_id]/[card_id]', function ($story_id, $card_id) {
      require user::dir(-1).'/service/tran/upload_card.php';
  }); // 更改投稿

  build::post('story/tran/comment/[card_id]/[to]', function ($card_id, $to) {
      require user::dir(-1).'/service/tran/comment.php';
  }); // 发送留言

  build::post('story/tran/reply/[comment_id]/[to]', function ($comment_id, $to) {
      require user::dir(-1).'/service/tran/reply.php';
  }); // 回复留言

  build::get('story/tran/delete_comment/[comment_id]', function ($comment_id) {
      require user::dir(-1).'/service/tran/delete_comment.php';
  }); // 删除留言
  */
