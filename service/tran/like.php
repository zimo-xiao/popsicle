<?php

/*
|--------------------------------------------------------------------------
| 点/取消赞
|--------------------------------------------------------------------------
|
| 输入
| - $action：0点赞、1取消
|
|
*/

// 卡片权重公式
function weight($likes)
{
    return 5000*exp(($likes)*0.01);
}

if (!$cards = sql::select('cards')->where('id=?', [$card_id])->limit(1)->fetch()) {
    echo '非法文章ID';
} else {
    $likes = sql::select('likes')->where('card_id=?', [$card_id])->count();
    if ($action==='0') {
        // 如果用户登录，则更改用户tag状态
        session_start();
        if (isset($_SESSION['openid'])) {
            $tag_weight = 5;
            $story_id = $cards['story_id'];
            require user::dir().'/service/plug/add_tag_weight.php';
        }

        // 点赞
        sql::insert('likes')->this([
          user::ip(),
          user::agent(),
          $card_id
        ]);
        sql::update('cards')->this([
          'weight' => $cards['weight'] + weight($likes)
        ])->where('id', [$card_id])->limit(1);
        sql::update('stories')->this('count_likes = count_likes+1', [])->where('id', [$cards['story_id']])->limit(1);
        $likes++;
        echo '<div class="icon_selected center" onclick="ajax_get(\''.user::url().'/story/tran/like/'.$card_id.'/1\',\'#like_'.$card_id.'\')"><i class="far fa-thumbs-up"></i>&nbsp;';

        // 如果满足阈值，则向用户推送鼓励小文章
        if ($likes%25===0) {
            $like_text = [
              [
                '汇报投稿反馈！',
                '也许你已经不记得在Z校园有过投稿，但我们没有忘记你哦💕。大家很喜欢你的投稿，有'.$likes.'个读者给你点赞了呢。\n\n要不要回来看一看他们呢？'
              ],
              [
                '打扰了，老朋友',
                '还记得你在Z校园的投稿嘛？\n我们和'.$likes.'个为你点赞的陌生人都很喜欢呢❤️ 也许只是你匿名留下的一个脚印，但踩中了我们的心呢。\n\n要不要回来看看你走过的路呢？'
              ]
            ];  // 点赞文案
            $like_text = $like_text[array_rand($like_text)];

            $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
            $token = $wx->access_token();
            $wx->tmp_return($token, [
              'to' => $cards['openid'],
              'id' => 'jkpdMYTWX3W8tJ3rxf5RoD5W0NmwhvaaFXkjvClrEDY',
              'url' => user::url().'/story/tran/fetch_openid/'.$cards['openid'].'/story+'.$cards['story_id'],
              'data' => [
                'first' => [
                    'value' => $like_text[0]
                  ],
                'keyword1' => [
                    'value' => '点赞提醒',
                    'color' => '#808080'
                  ],
                'keyword2' => [
                    'value' => date('H:i'),
                    'color' => '#808080'
                  ],
                'remark' => [
                    'value' => $like_text[1],
                    'color' => '#808080'
                  ]
              ]
            ]);
        }
    } elseif ($action==='1') {
        // 取消
        sql::delete('likes')->where('ip=? and agent=? and card_id=?', [
          user::ip(),
          user::agent(),
          $card_id
        ])->execute();
        sql::update('cards')->this([
          'weight' => $cards['weight'] - weight($likes)
        ])->where('card_id', [$card_id])->limit(1);
        sql::update('stories')->this('count_likes = count_likes-1', [])->where('id', [$cards['story_id']])->limit(1);
        $likes--;
        echo '<div class="icon center" onclick="ajax_get(\''.user::url().'/story/tran/like/'.$card_id.'/0\',\'#like_'.$card_id.'\')"><i class="far fa-thumbs-up"></i>&nbsp;';
    }
    echo $likes.'</div>';
}
