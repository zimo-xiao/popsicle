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
      $wx->return('news', [
        'to' => $input->FromUserName,
        'articles' => [[
          'title' => $story_data['title'],
          'description' => $story_data['description'],
          'picurl' => '',
          'url' => user::url().'/story/tran/fetch_openid/'.$input->FromUserName.'/story+'.$story_id
        ]]
      ]);
  }
