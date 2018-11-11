<?php
  $date = date_create();
  date_add($date, date_interval_create_from_date_string(user::post('time')." days"));
  $time_id = date_format($date, "Ymd");

  $story_data = sql::select('stories')->where('id=?', [user::post('id')])->limit(1)->fetch()[0];
  if (!$story_data) {
      js::alert('id填写错误');
      jump::back(-1);
      exit;
  } // 验证访问页面

  $tags = explode('/', $story_data['tags']);

  if (sql::select('schedules')->where('time=?', [$time_id])->limit(1)->fetch()[0]) {
      js::alert('这一天已经有安排了，请换一天');
      jump::back(-1);
      exit;
  } // 验证时间


  sql::insert('schedules')->this([
    $time_id,
    user::post('text'),
    'story+'.user::post('id')
  ]);

  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  $access_token = $wx->access_token();
  $tag_list = $wx->get_all_tags($access_token);
  foreach ($tag_list as $tag) {
      if (is::in($tag['name'], $tags)) {
          $users = $wx->get_all_users_under_tag($access_token, $tag['id']);
          foreach ($users['data']['openid'] as $user) {
              if (!sql::select('noti_pool')->where('time=? and openid=?', [$time_id, $user])->limit(1)->fetch()[0]) {
                  sql::insert('noti_pool')->this([
                    $time_id,
                    $user,
                    $tag['name']
                  ]);
              }
          }
      }
  }

  js::alert('安排上了');
  jump::to(user::url().'/story/'.user::post('id'));
