<?php

/*
|--------------------------------------------------------------------------
| 向全社团人生成本周数据
|--------------------------------------------------------------------------
|
| 输出
| - 向微信输出report
|
|
*/

$wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
$access_token = $wx->access_token(user::dir(-2));
$last = sql::select('reports')->order('report_time')->by('desc')->limit(1)->fetch()[0]; // 获取上次数据
$visits = sql::select('visits')->count() - $last['visits']; // 计算访问人数
$likes = sql::select('likes')->count() - $last['likes']; // 计算点赞人数
$cards = sql::select('cards')->count() - $last['cards']; // 计算点赞人数
// 计算7天内增长人数
$date = date_create(date('Y-m-d'));
date_sub($date, date_interval_create_from_date_string('7 days'));
$start_date = date_format($date, 'Y-m-d');
$date = date_create(date('Y-m-d'));
date_sub($date, date_interval_create_from_date_string('1 days'));
$end_date = date_format($date, 'Y-m-d');
$cumulate_users = json_decode(curl::post('https://api.weixin.qq.com/datacube/getusercumulate?access_token='.$access_token, json_encode([
  'begin_date' => $start_date,
  'end_date' => $end_date
])), true)['list'];
// 生成报告
$report = '本周新增投稿：'.$cards.'
本周公众号新增关注：'.(end($cumulate_users)['cumulate_user']-$cumulate_users[0]['cumulate_user']).'
公众号总共关注量：'.$cumulate_users[0]['cumulate_user'].'
本周新增点赞数：'.$likes.'
本周新增独一浏览量：'.$visits;
foreach ($GLOBALS['admin'] as $admin_id) {
    $wx->tmp_return($access_token, [
      'to' => $admin_id,
      'id' => '6rmOfAAis6_2meOay4Epm6G4Tq3K5_CIrs-bIWTf5ic',
      'url' => '',
      'data' => [
        'first' => [
            'value' => 'Popsicle本周报告，请享用 🍦',
            'color' => '#808080'
          ],
        'keyword1' => [
            'value' => '小冰棍',
            'color' => '#808080'
          ],
        'keyword2' => [
            'value' => date('Y/m/d'),
            'color' => '#808080'
          ],
        'keyword3' => [
            'value' => '神奇的海螺',
            'color' => '#808080'
          ],
        'remark' => [
            'value' => $report,
            'color' => '#808080'
          ]
      ]
    ]);
}

sql::insert('reports')->this([
  'report_time' => date('Ymd'),
  'likes' => $likes+$last['likes'],
  'cards' => $cards+$last['cards'],
  'visits' => $visits+$last['visits'],
]);
