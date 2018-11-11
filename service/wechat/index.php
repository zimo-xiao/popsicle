<?php
/*
|--------------------------------------------------------------------------
| 处理来自微信公众号的请求
|--------------------------------------------------------------------------
|
| 文本规则：
| - password：返回本人openid
| - 后台：验证是否为管理员，然后返回操作后台
|
| 订阅规则：
|   如果是通过扫描带参数二维码而订阅的，直接执行扫描二维码代码
|   反之，则返回订阅欢迎文案
|
| 菜单规则：
| - 没想好，招人？yyn你要不要在群里讨论一下
|
| 扫码所传递的参数类型：
|   1、一周一故事story_id：story_开头（例：story_1）
|   2、没想好留着以后拓展
|
|
|
*/

  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);



  // 文本监听区

  $wx->listen('text', 'password', function ($input, $wx) {
      $wx->return('text', [
        'to' => $input->FromUserName,
        'content' => $input->FromUserName
      ]);
  }); //获取本人openid

  $wx->listen('text', '添加雪糕', function ($input, $wx) {
      if (is::in($input->FromUserName, $GLOBALS['admin'])) {
          $wx->return('news', [
            'to' => $input->FromUserName,
            'articles' => [[
              'title' => '添加雪糕',
              'description' => '🍦欢迎Popsicle的小伙伴',
              'picurl' => '',
              'url' => user::url().'/story/tran/fetch_openid/'.$input->FromUserName.'/story+admin+create'
            ]]
          ]);
      } else {
          $wx->return('text', [
            'to' => $input->FromUserName,
            'content' => '你没有权限操作'
          ]);
      }
  }); //返回添加雪糕

  $wx->listen('text', '批准雪糕', function ($input, $wx) {
      if (is::in($input->FromUserName, $GLOBALS['admin'])) {
          $wx->return('news', [
            'to' => $input->FromUserName,
            'articles' => [[
              'title' => '批准后台',
              'description' => '🍦欢迎Popsicle的小伙伴',
              'picurl' => '',
              'url' => user::url().'/story/tran/fetch_openid/'.$input->FromUserName.'/story+admin+unactivated+card'
            ]]
          ]);
      } else {
          $wx->return('text', [
            'to' => $input->FromUserName,
            'content' => '你没有权限操作'
          ]);
      }
  }); //返回批准后台

  $wx->listen('text', '添加词条', function ($input, $wx) {
      if (is::in($input->FromUserName, $GLOBALS['admin'])) {
          $wx->return('news', [
            'to' => $input->FromUserName,
            'articles' => [[
              'title' => '添加词条',
              'description' => '🍦欢迎Popsicle的小伙伴',
              'picurl' => '',
              'url' => user::url().'/story/tran/fetch_openid/'.$input->FromUserName.'/dict+admin+create'
            ]]
          ]);
      } else {
          $wx->return('text', [
            'to' => $input->FromUserName,
            'content' => '你没有权限操作'
          ]);
      }
  }); //返回添加雪糕

  $wx->listen('text', '批准词条', function ($input, $wx) {
      if (is::in($input->FromUserName, $GLOBALS['admin'])) {
          $wx->return('news', [
            'to' => $input->FromUserName,
            'articles' => [[
              'title' => '批准词条',
              'description' => '🍦欢迎Popsicle的小伙伴',
              'picurl' => '',
              'url' => user::url().'/story/tran/fetch_openid/'.$input->FromUserName.'/dict+admin+unactivated+card'
            ]]
          ]);
      } else {
          $wx->return('text', [
            'to' => $input->FromUserName,
            'content' => '你没有权限操作'
          ]);
      }
  }); //返回批准后台

  $wx->listen('text', '推送词条', function ($input, $wx) {
      if (is::in($input->FromUserName, $GLOBALS['admin'])) {
          $wx->return('news', [
            'to' => $input->FromUserName,
            'articles' => [[
              'title' => '推送词条',
              'description' => '🍦欢迎Popsicle的小伙伴',
              'picurl' => '',
              'url' => user::url().'/story/tran/fetch_openid/'.$input->FromUserName.'/dict+admin+schedule'
            ]]
          ]);
      } else {
          $wx->return('text', [
            'to' => $input->FromUserName,
            'content' => '你没有权限操作'
          ]);
      }
  }); //返回批准后台

  $wx->listen('text', '推送雪糕', function ($input, $wx) {
      if (is::in($input->FromUserName, $GLOBALS['admin'])) {
          $wx->return('news', [
            'to' => $input->FromUserName,
            'articles' => [[
              'title' => '推送雪糕',
              'description' => '🍦欢迎Popsicle的小伙伴',
              'picurl' => '',
              'url' => user::url().'/story/tran/fetch_openid/'.$input->FromUserName.'/story+admin+schedule'
            ]]
          ]);
      } else {
          $wx->return('text', [
            'to' => $input->FromUserName,
            'content' => '你没有权限操作'
          ]);
      }
  }); //返回批准后台



  // 订阅监听区

  $wx->listen('event', 'subscribe', function ($input, $wx) {
      if (is::in('qrscene_', $input->EventKey)) {
          $input->EventKey = str::replace('qrscene_', '', $input->EventKey);
          // 如果是通过有参数二维码订阅
          require user::dir().'/service/plug/wechat/scan.php';
      } else {
          // 反之则返回订阅文案
          $wx->return('text', [
            'to' => $input->FromUserName,
            'content' => '嗨，我们等你好久啦~
看往期推送请戳菜单栏哟'
          ]);
      }
  });



  // 菜单栏点击监听区

  $wx->listen('event', 'CLICK', function ($input, $wx) {
      require user::dir().'/service/plug/wechat/click.php';
  });



  // 扫描监听区

  $wx->listen('event', 'SCAN', function ($input, $wx) {
      require user::dir().'/service/plug/wechat/scan.php';
  });



  // 例外监听区（不满足任何规则的）

  $wx->listen('text', 'empty', function ($input, $wx) {
      require user::dir().'/service/plug/wechat/empty.php';
  });



  $wx->run(); //执行监听
