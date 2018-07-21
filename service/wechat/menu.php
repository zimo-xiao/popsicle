<?php
/*
|--------------------------------------------------------------------------
| 公众号底部菜单栏
|--------------------------------------------------------------------------
|
|一周一故事下三个按钮
|最新：从数据库中选择4个最新的可用故事
|合作&bug反馈：url跳转
|
|
|
|
*/

  $menu = [[
    'type' => 'click',
    'name' => 'test',
    'key' => 't'
  ],[
    'name' => '一周一故事',
    'sub_button' => [
      [
        'type' => 'click',
        'name' => '最新',
        'key' => 'story_more'
      ],[
        'type' => 'view',
        'name' => '合作',
        'url' => 'http://zuggrcampus.mikecrm.com/dOEmXVd'
      ],[
        'type' => 'view',
        'name' => 'bug反馈',
        'url' => 'http://zuggrcampus.mikecrm.com/5xMF9ac'
      ]
    ]
  ]];
  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  echo $wx->menu($wx->access_token(), $menu);
