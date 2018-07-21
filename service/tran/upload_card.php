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
  if (is::empty(user::file('image')) and is::empty(user::post('text'))) {
      // 如果既没有图片，也没有文字
      js::alert('文字或图片不可为空');
      jump::back(-1);
      exit();
  } else {

      // 处理图片&验证
      if (!isset($card_id)) {
          // 如果是新投稿
          $card_id = $weight.$story_id.rand(0, 99);
          $img = '';
          if (!is::empty(user::file('image'))) {
              $img = img::upload(user::file('image'), 'card_'.str::random(20), 60);
          }
      } else {
          // 如果是更改投稿
          if ($org = sql::select('cards')->where('id=? and activate=1', [$card_id])->limit(1)->fetch()) {
              // 如果卡片存在
              if ($org===user::post('text') && is::empty(user::file('image'))) {
                  // 如果用户什么都没变（更改图片需要重新上传一张）
                  js::alert('请进行修改');
                  jump::back(-1);
                  exit();
              } else {
                  if (is::empty(user::file('image'))) {
                      $img = $org['img']; // 如果没更改图片，我们就用原来的
                  } else {
                      $img = img::upload(user::file('image'), 'card_'.str::random(20), 60);
                  }
              }
          } else {
              js::alert('故事ID不存在');
              jump::back(-1);
              exit();
          }
      }

      // 上传故事
      sql::insert('cards')->this([
        $card_id,
        $weight,
        $story_id,
        $_SESSION['openid'],
        str::html(user::post('text')),
        $img,
        0
      ]);

      // 更改用户tag状态
      $tag_weight = 15;
      require user::dir().'/service/plug/add_tag_weight.php';

      // 随机分配审核
      $to = $admin[array_rand($admin)];
      $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
      $token = $wx->access_token();
      $wx->tmp_return($token, [
        'to' => $to,
        'id' => '-hIHETId6A5yDSJW1jjOd3V4hI_MzCiDTfea9Q1HdWE',
        'url' => user::url().'/story/tran/fetch_openid/'.$to.'/story+admin+unactivated+card',
        'data' => [
          'first' => [
              'value' => '收到新故事，请批准 📝'
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
              'value' => user::post('text'),
              'color' => '#808080'
            ]
        ]
      ]);
      js::alert('匿名投稿成功💥！请耐心等待审核，将在10分钟内通知你哦，请持续关注Z校园公众号');
      jump::to(user::url().'/story/'.$id);
  }
