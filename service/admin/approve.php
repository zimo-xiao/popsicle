<?php

/*
|--------------------------------------------------------------------------
| 审核稿件
|--------------------------------------------------------------------------
|
| 输入
| - $card_id
| - $action
|
|
*/

  $c = sql::select('cards')->where('id=? and activate=0', [$card_id])->limit(1)->fetch()[0];
  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $access_token = $wx->access_token();
  $story_data = sql::select('stories')->where('id=?', [$c['story_id']])->limit(1)->fetch()[0];
  $color="#".$story_data['color'];

  if ($action==='1') {
      // 如果同意，将所有的历史记录全部设为不可读(2)
      // sql::update('cards')->this([
      //   'activate' => 2
      // ])->where('id=? and activate=1', [$card_id])->execute();

      // 将最新版本设为可读(1)
      sql::update('cards')->this([
        'activate' => 1
      ])->where('id=? and activate=0', [$card_id])->execute();

      $wx->tmp_return($access_token, [
        'to' => $c['openid'],
        'id' => 'gPWptcu-FfKSQ3ujwN3GchFxDxyD5S9WpnakHs0iBjI',
        'url' => user::url().'/story/tran/fetch_openid/'.$c['openid'].'/story+'.$c['story_id'],
        'data' => [
          'first' => [
              'value' => '点击分享属于你的那一层雪糕吧',
              'color' => $color
            ],
          'keyword1' => [
              'value' => $story_data['title'],
              'color' => '#808080'
            ],
          'keyword2' => [
              'value' => date('H:i'),
              'color' => '#808080'
            ],
          'keyword3' => [
              'value' => '+1',
              'color' => '#808080'
            ],
          'remark' => [
              'value' => $c['content'],
              'color' => '#808080'
            ]
        ]
      ]);

      $update = sql::select('stories')->where('id=?', [$c['story_id']])->limit(1)->fetch()[0]['count_cards']+1;
      sql::update('stories')->this([
        'count_cards' => $update
      ])->where('id=?', [$c['story_id']])->limit(1)->execute();
  } elseif ($action==='0') {
      // 将最新版本设为不可读(2)
      sql::update('cards')->this([
        'activate' => 2
      ])->where('id=? and activate=0', [$card_id])->execute();

      // $wx->tmp_return($access_token, [
      //   'to' => $c['openid'],
      //   'id' => 'rtZWQxhefmpJY6X2k1Vv0MWWNQy2yz1fmxgPaEYIflA',
      //   'url' => user::url().'/story/tran/fetch_openid/'.$c['openid'].'/story+'.$c['story_id'],
      //   'data' => [
      //     'first' => [
      //         'value' => '投稿驳回，请点击重新上传',
      //         'color' => $color
      //       ],
      //     'keyword1' => [
      //         'value' => $story_data['title'],
      //         'color' => '#808080'
      //       ],
      //     'keyword2' => [
      //         'value' => '内容不合适',
      //         'color' => '#808080'
      //       ],
      //     'remark' => [
      //         'value' => $c['content'],
      //         'color' => '#808080'
      //       ]
      //   ]
      // ]);
  }

  js::alert('审核成功！');
  jump::to(user::url().'/story/admin/unactivated/card');
