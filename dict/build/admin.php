<?php

  // Admin Routers: 后台操作

  build::get('dict/admin/approve/[card_id]/[action]', function ($card_id, $action) {
      require user::dir(-1).'/service/admin/approve.php';
  }); // 批准card

  build::get('dict/admin/activate/[story_id]', function ($story_id) {
      require user::dir(-1).'/service/admin/activate.php';
  }); // 激活story

  build::get('dict/admin/create', function () {
      echo view::render('admin/create.html', [
        'style' => view::style([
          user::url().'/dict/view/file/style/icon.css',
          user::url().'/dict/view/file/style/main.css',
          user::url().'/dict/view/file/style/form.css'
        ]),
        'script' => view::script([
          'https://res.wx.qq.com/open/js/jweixin-1.2.0.js',
          'https://libs.baidu.com/jquery/1.9.1/jquery.min.js',
          user::url().'/dict/view/file/script/main.js'
        ]),
        'bottom_script' => view::script([
          user::url().'/dict/view/file/script/form.js',
        ]),
        'url' => user::url()
      ]);
  }); // 生成story

  build::get('dict/admin/schedule', function () {
      echo view::render('admin/schedule.html', [
        'style' => view::style([
          user::url().'/dict/view/file/style/icon.css',
          user::url().'/dict/view/file/style/main.css',
          user::url().'/dict/view/file/style/form.css'
        ]),
        'script' => view::script([
          'https://res.wx.qq.com/open/js/jweixin-1.2.0.js',
          'https://libs.baidu.com/jquery/1.9.1/jquery.min.js',
          user::url().'/dict/view/file/script/main.js'
        ]),
        'bottom_script' => view::script([
          user::url().'/dict/view/file/script/form.js',
        ]),
        'url' => user::url()
      ]);
  }); // 生成story

  build::post('dict/admin/create', function () {
      require user::dir(-1).'/service/admin/create.php';
  });

  build::post('dict/admin/schedule', function () {
      require user::dir(-1).'/service/admin/schedule.php';
  });
