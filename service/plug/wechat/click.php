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
    case 'hezuo':
    $wx->return('news', [
       'to' => $input->FromUserName,
       'articles' => [[
         'title' => '合作',
         'description' => '如有合作需求，请联系Z校园社长洪林霞
请点击此推送以获取社长联系方式',
         'picurl' => '',
         'url' => 'https://xy.zuggr.com/view/file/img/qrContact.jpeg'
       ]]
     ]);
    break;
    case 'story_more':
    $stories = sql::select('stories')->where('activate=1')->order('id')->by('desc')->limit(4)->fetch();
       $out = [];
       foreach ($stories as $story) {
           if ($card_info = sql::select('cards')->where('story_id=? and activate=1 and img<>\'\'', [$story['id']])->order('weight')->by('desc')->limit(1)->fetch()) {
               $picurl = 'https://xy.zuggr.com/file/img/'.$card_info[0]['img'];
           } else {
               $picurl = '';
           }
           $out[] = [
           'title' => $story['title'],
           'description' => str::utf8($story['description']),
           'picurl' => $picurl,
           'url' =>'https://xy.zuggr.com/story/'.$story['id']
         ];
       }
       $wx->return('news', [
         'to' => $input->FromUserName,
         'articles' => $out
       ]);
    break;
  }
