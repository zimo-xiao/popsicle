<?php

/*
|--------------------------------------------------------------------------
| 为用户添加tag，并更新权重
|--------------------------------------------------------------------------
|
| 输入
| - $story_id
| - $tag_weight
|
*/

$story_info = sql::select('stories')->where('id=?', [$story_id])->limit(1)->fetch()[0];
$t = str::split('/', $story_info['tags']);
foreach ($t as $tag) {
    if ($tags = sql::select('tags')->where('openid=?', [$_SESSION['openid']])->limit(1)->fetch()) {
        $tags = $tags[0];
        sql::update('tags')->this([
          'weight' => $tags['weight']+$tag_weight
        ])->where('openid=? and tag=?', [$_SESSION['openid'], $tag])->execute();
    } else {
        sql::insert('tags')->this([
          $_SESSION['openid'],
          $tag,
          $tag_weight
        ]);
    }
}
