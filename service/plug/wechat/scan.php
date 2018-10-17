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
          $wx->return('text', [
            'to' => $input->FromUserName,
            'content' => $qr_value[1]
          ]);
      }
  }
