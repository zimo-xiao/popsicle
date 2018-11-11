<?php
/*
|--------------------------------------------------------------------------
| 自动更新用户列表
|--------------------------------------------------------------------------
|
| 向微信请求所有用户openid，并更新个人信息，插入数据库
|
|
*/

  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $access_token = $wx->access_token();

  $today = date_format(date_create(), "Ymd");
  $noti = sql::select('schedules')->where('time=?', [$today])->limit(1)->fetch()[0];
  if ($noti) {
      $users = sql::select('noti_pool')->where('time=?', [$today])->limit(30)->fetch();
      foreach ($users as $user) {
          $wx->tmp_return($access_token, [
            'to' => $user['openid'],
            'id' => 'MzjDQQjskwEN0LgorBsqBWarP_sJOxXP2Tn-FK8LtS8',
            'url' => user::url().'/story/tran/fetch_openid/'.$user['openid'].'/'.$noti['url'],
            'data' => [
              'first' => [
                  'value' => '向'.$user['tag'].'学生的一份邀请',
                  'color' => '#808080'
                ],
              'keyword1' => [
                  'value' => '🍦',
                  'color' => '#808080'
                ],
              'keyword2' => [
                  'value' => date_format(date_create(), "Y-m-d"),
                  'color' => '#808080'
                ],
              'keyword3' => [
                  'value' => '海淀黄庄大泥湾路',
                  'color' => '#808080'
                ],
              'remark' => [
                  'value' => $noti['text'],
                  'color' => '#808080'
                ]
            ]
          ]);
          sql::delete('noti_pool')->where('time=? and openid=?', [$today, $user['openid']])->limit(1)->execute();
      }
  }

  //
  // if (isset($qr_data['tags'])) {
  //     $access_token = $wx->access_token();
  //     $tag_list = $wx->get_all_tags($access_token);
  //     foreach ($tag_list as $k_a => $v_a) {
  //         foreach ($qr_data['tags'] as $v_b) {
  //             if ($v_a['name'] == $v_b) {
  //                 $wx->add_tags($access_token, [(string)$input->FromUserName[0]], $v_a['id']);
  //                 break;
  //             }
  //         }
  //     }
  // }
  //
  //
  // $out = $wx->get_all_user_id($at);
  // foreach ($out as $openid) {
  //     if (!sql::select('users')->where('openid=?', [$openid])->limit(1)->fetch()) {
  //         $user = $wx->get_user_info($at, $openid);
  //         $headimg = $user->headimgurl;
  //         $sex = $user->sex;
  //         $name = $user->nickname;
  //         sql::insert('users')->this([$openid,$name,$sex,$headimg]);
  //     }
  // }
