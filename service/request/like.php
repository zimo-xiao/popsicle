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
    $cards = $cards[0];
    $likes = sql::select('likes')->where('card_id=?', [$card_id])->count();
    if ($action==='1') {
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

        // 如果满足阈值，则向用户推送鼓励小文章
        $story_data = sql::select('stories')->where('id=?', [$cards['story_id']])->limit(1)->fetch()[0];
        if ($likes%25===0) {
            $like_text = [
              [
                '还记得嘛？',
                '也许你已经不记得在‘'.$story_data['title'].'’投过稿，但我们没有忘记你。大家很喜欢你的那一段回忆，有'.$likes.'个读者给你点赞了呢。
要不要回来看一看他们呢？'
              ],
              [
                '打扰了，老朋友',
'还记得你储存在‘'.$story_data['title'].'’上的记忆嘛？
我们和'.$likes.'个为你点赞的人都很喜欢呢
要不要回来看看你曾经的回忆呢？'
              ]
            ];  // 点赞文案
            $like_text = $like_text[array_rand($like_text)];

            $wx = new angel\wechat($GLOBALS['wechat_config']['appid'], $GLOBALS['wechat_config']['secret'], $GLOBALS['wechat_config']['token']);
            $access_token = $wx->access_token();
            $wx->tmp_return($access_token, [
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
        echo '<font onclick="ajax_get(\''.user::url().'/story/request/like/'.$card_id.'/0\',\'like_'.$card_id.'\')" class="liked"><i class="far fa-thumbs-up"></i>&nbsp;';
    } elseif ($action==='0') {
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
        echo '<font onclick="ajax_get(\''.user::url().'/story/request/like/'.$card_id.'/1\',\'like_'.$card_id.'\')" class="like"><i class="far fa-thumbs-up"></i>&nbsp;';
    }
    echo $likes.'</font>';
}
