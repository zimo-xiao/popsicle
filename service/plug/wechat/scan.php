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
      $story_id =  str::replace('qrscene_', '', $value);
      $story_data = sql::select('stories')->where('story_id=?', $story_id)->limit(1)->fetch()[0];
      if ($story_id['activate']==='2') {
          // 如果是隐藏故事
      } elseif ($story_id['activate']==='1') {
          // 如果是正常故事
      }
  }
