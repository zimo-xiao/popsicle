<?php
/*
|--------------------------------------------------------------------------
| 自动删除过时的故事
|--------------------------------------------------------------------------
|
| 数据库查询过久未激活的主题及相关的卡片并删除
| $activate=2 表示故事/卡片未激活
|
*/


  $max = sql::select('stories')->order('id')->by('desc')->limit(1)->fetch();
  $max_id = $max['id']-4;
  sql::delete('stories')->where('id<? and activate=2', [$max_id])->execute();
  sql::delete('cards')->where('story_id<? and activate=2', [$max_id])->execute();
