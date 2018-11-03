<?php
/*
|--------------------------------------------------------------------------
| 处理扫码请求
|--------------------------------------------------------------------------
|
| 输入：
| - $input
| - $wx
|
|
|
*/

  $qr_value = $input->EventKey;  // 扫码所传递的参数
  $qr_data = json_decode(sql::select('qrs')->where('service=?', [$qr_value])->limit(1)->fetch()[0]['return_data'], true);
  // 如果有tag信息，为扫描用户添加tag
  if (isset($qr_data['tags'])) {
      $access_token = $wx->access_token();
      $tag_list = $wx->get_all_tags($access_token);
      foreach ($tag_list as $k_a => $v_a) {
          foreach ($qr_data['tags'] as $v_b) {
              if ($v_a['name'] == $v_b) {
                  $wx->add_tags($access_token, [(string)$input->FromUserName[0]], $v_a['id']);
                  break;
              }
          }
      }
  }

  // 如果有权限设置
  if (isset($qr_data['only'])) {
      if ($qr_data['only'] != $input->FromUserName) {
          exit;
      }
  }

  if (is::in('story_', $qr_value)) {
      $story_id =  str::replace('story_', '', $qr_value);
      $story_data = sql::select('stories')->where('id=? and activate=1', [$story_id])->limit(1)->fetch()[0];
      if ($card_info = sql::select('cards')->where('story_id=? and activate=1 and img<>\'\'', [$story_data['id']])->order('weight')->by('desc')->limit(1)->fetch()) {
          $picurl = 'https://xy.zuggr.com/file/img/'.$card_info[0]['img'];
      } else {
          $picurl = '';
      }
      $wx->return('news', [
        'to' => $input->FromUserName,
        'articles' => [[
          'title' => $story_data['title'],
          'description' => str::utf8($story_data['description']),
          'picurl' => $picurl,
          'url' => user::url().'/story/tran/fetch_openid/'.$input->FromUserName.'/story+'.$story_id
        ]]
      ]);
  } else {
      $qr_value = explode('_', $qr_value);
      if ($GLOBALS['open_code'][$qr_value[0]]!='') {
          if (isset($qr_data['action'])) {
              if (is::in('?', $qr_data['action'])) {
                  $return_data = curl::get($qr_data['action'].'&openid='.$input->FromUserName);
              } else {
                  $return_data = curl::get($qr_data['action'].'?openid='.$input->FromUserName);
              }
              $wx->return('text', [
                'to' => $input->FromUserName,
                'content' => $return_data
              ]);
          } elseif ($qr_data['message']) {
              $wx->return('text', [
                'to' => $input->FromUserName,
                'content' => $qr_data['message']
              ]);
          }
      }
  }
