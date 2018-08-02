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
    'name' => 'Popsicle',
    'sub_button' => [
      [
        'type' => 'click',
        'name' => '最新',
        'key' => 'story_more'
      ],[
        'type' => 'click',
        'name' => '合作',
        'key' => 'hezuo'
      ],[
        'type' => 'view',
        'name' => 'bug反馈',
        'url' => 'https://xy.zuggr.com/story/1'
      ]
    ]
  ],[
    'type' => 'view',
    'name' => '关于我们',
    'url' => 'https://xy.zuggr.com/story/1'
  ]];
  $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
  echo $wx->menu($wx->access_token(), $menu);
