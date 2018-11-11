<?php
/*
|--------------------------------------------------------------------------
| 渲染批准界面
|--------------------------------------------------------------------------
|
*/

  session_start();
  $openid = isset($_SESSION['openid'])?$_SESSION['openid']:'null';
  if (is::in($openid, $GLOBALS['admin'])) {
      view::global(['url' => user::url()]);
      $out = '';

      if ($cards = sql::select('cards')->where('activate=0', [])->order('weight')->by('desc')->fetch()) {
          foreach ($cards as $v) {
              $out .= view::render('admin/approve_card.html', [
                'nick' => $v['nick'],
                'card_id' => $v['id'],
                'is_img' => view::if($v['img']!=''),
                'img' => $v['img'],
                'text' => is::empty($v['content']) ? '&nbsp;' : $v['content']
              ]);
          }
      } else {
          js::alert('没有审核');
      }

      $resource_url = user::url().'/dict/view/file/';

      echo view::render('admin/main.html', [
        'style' => view::style([
          $resource_url.'style/icon.css',
          $resource_url.'style/main.css',
          $resource_url.'style/form.css'
        ]),
        'script' => view::script([
          'https://libs.baidu.com/jquery/1.9.1/jquery.min.js',
          $resource_url.'script/lazy.js',
          $resource_url.'script/main.js'
        ]),
        'bottom_script' => view::script([
          $resource_url.'script/form.js',
          $resource_url.'script/bottom_main.js'
        ]),
        'main' => $out,
        'title' => '未审核'
      ]);
  } else {
      echo '你没有权限';
  }
