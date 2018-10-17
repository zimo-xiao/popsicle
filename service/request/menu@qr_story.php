<?php

/*
|--------------------------------------------------------------------------
| 返回带参数的二维码
|--------------------------------------------------------------------------
|
| 输入
| - $story_id
|
| 输出
| - 渲染好的qr
|
|
*/

$ticket = sql::select('qrs')->where('service=?', ['story_'.$story_id])->limit(1)->fetch()[0];
$wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
if ($ticket) {
    echo view::render('story/qr.html', [
      'qr' => $wx->get_qr_img($ticket['ticket']),
      'text' => '长按扫描二维码登录&nbsp;<i class="far fa-hand-pointer"></i>'
    ]);
} else {
    echo view::render('story/qr.html', [
      'qr' =>'https://xy.zuggr.com/view/file/img/head.jpg',
      'text' => '该雪糕未被激活'
    ]);
}
