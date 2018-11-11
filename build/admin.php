<?php

  // Admin Routers: 后台操作

  build::get('story/admin/approve/[card_id]/[action]', function ($card_id, $action) {
      require user::dir(-1).'/service/admin/approve.php';
  }); // 批准card

  build::get('story/admin/activate/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/admin/activate.php';
  }); // 激活story

  build::get('story/admin/create', function () {
      echo view::render('admin/create.html', [
        'style' => view::style([
          user::url().'/view/file/style/icon.css',
          user::url().'/view/file/style/main.css',
          user::url().'/view/file/style/form.css'
        ]),
        'script' => view::script([
          'https://res.wx.qq.com/open/js/jweixin-1.2.0.js',
          'https://libs.baidu.com/jquery/1.9.1/jquery.min.js',
          user::url().'/view/file/script/main.js'
        ]),
        'bottom_script' => view::script([
          user::url().'/view/file/script/form.js',
        ]),
        'url' => user::url()
      ]);
  }); // 生成story

  build::get('story/admin/schedule', function () {
      echo view::render('admin/schedule.html', [
        'style' => view::style([
          user::url().'/view/file/style/icon.css',
          user::url().'/view/file/style/main.css',
          user::url().'/view/file/style/form.css'
        ]),
        'script' => view::script([
          'https://res.wx.qq.com/open/js/jweixin-1.2.0.js',
          'https://libs.baidu.com/jquery/1.9.1/jquery.min.js',
          user::url().'/view/file/script/main.js'
        ]),
        'bottom_script' => view::script([
          user::url().'/view/file/script/form.js',
        ]),
        'url' => user::url()
      ]);
  }); // 生成story

  build::post('story/admin/create', function () {
      require user::dir(-1).'/service/admin/create.php';
  });

  build::post('story/admin/schedule', function () {
      require user::dir(-1).'/service/admin/schedule.php';
  });

  // 未实现
  /*
  build::get('story/admin/invite/[tag]', function ($tag) {
      require user::dir(-1).'/service/admin/invite.php';
  }); // 邀请一个tag的user
  */
