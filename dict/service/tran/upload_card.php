<?php

/*
|--------------------------------------------------------------------------
| 上传投稿（图片+文字），并通知相关人员审核
|--------------------------------------------------------------------------
|
| 输入
| - $story_id
| - $card_id：更改投稿时所传递的值
|
|
*/

  session_start();
  if (!isset($_SESSION['openid'])) {
      // 如果未登录
      js::alert('用户未登录');
      jump::back(-1);
      exit();
  }

  $weight = date('YmdHis');
  $text = '';
  if (!is::empty(user::post('text_explain'))) {
      $text .= '<p><strong>解释：</strong><br>'.str::html(user::post('text_explain')).'</p>';
  }
  if (!is::empty(user::post('text_example'))) {
      $text .= '<p><strong>用法：</strong><br>'.str::html(user::post('text_example')).'</p>';
  }
  if (!is::empty(user::post('text_source'))) {
      $text .= '<p><strong>来源：</strong><br>'.str::html(user::post('text_source')).'</p>';
  }

  if (is::empty($text)) {
      // 如果既没有图片，也没有文字
      js::alert('文字不可为空');
      jump::back(-1);
      exit();
  } else {

      // 处理图片&验证
      $card_id = $weight.$story_id.rand(0, 99);
      $img = '';
      if (!is::empty(user::post('str_file'))) {
          $data = base64_decode(substr(user::post('str_file'), strpos(user::post('str_file'), ',') + 1));
          $img = 'card_'.str::random(20).'.jpg';
          file_put_contents(user::dir().'/file/img/'.$img, $data);
      }

      $nick = is::empty(user::post('nick')) ? '匿名' : user::post('nick');

      // 上传故事
      sql::insert('cards')->this([
        $card_id,
        $weight,
        $story_id,
        $_SESSION['openid'],
        $text,
        $img,
        $nick,
        0
      ]);

      // 更改用户tag状态
      $tag_weight = 15;
      require user::dir().'/service/plug/add_tag_weight.php';

      // 随机分配审核
      $to = $GLOBALS['admin'][array_rand($GLOBALS['admin'])];
      $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
      $access_token = $wx->access_token(user::dir(-2));
      $wx->tmp_return($access_token, [
        'to' => $to,
        'id' => '-hIHETId6A5yDSJW1jjOd3V4hI_MzCiDTfea9Q1HdWE',
        'url' => user::url().'/story/tran/fetch_openid/'.$to.'/dict+admin+unactivated+card',
        'data' => [
          'first' => [
              'value' => '收到新卡片投稿，请批准 📝'
            ],
          'keyword1' => [
              'value' => '匿名',
              'color' => '#808080'
            ],
          'keyword2' => [
              'value' => $story_info['title'],
              'color' => '#808080'
            ],
          'keyword3' => [
              'value' => date('H:i'),
              'color' => '#808080'
            ],
          'remark' => [
              'value' => str::utf8($text),
              'color' => '#808080'
            ]
        ]
      ]);
      js::alert('投稿成功💥！请耐心等待审核，将在10分钟内通知你哦，请持续关注Z校园公众号');
      jump::to(user::url().'/dict/'.$story_id);
  }
