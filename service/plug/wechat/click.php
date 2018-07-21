<?php
/*
|--------------------------------------------------------------------------
| 处理菜单栏点击请求
|--------------------------------------------------------------------------
|
| 输入：
| - $input
| - $wx
|
|
|
*/

  switch ($input->EventKey) {
    case 't':
    $wx->return('news',[
       'to' => $input->FromUserName,
       'articles' => [[
         'title' => '订阅成功！',
         'description' => 'abc',
         'picurl' => '',
         'url' => 'https://baidu.com'
       ]]
     ]);
    break;
    case 'event_b':
      // 样板2
    break;
  }
