<?php

  migration::table('stories', function ($table) {
      $table->bigint('id');
      $table->text('title');
      $table->text('prefix');
      $table->longtext('description');
      $table->text('tags');                 // 用"/"分割tag关键词
      $table->int('activate');              // 0未激活、1激活、2删除
      $table->longtext('ticket');           // 带参数二维码
      $table->bigint('freeze_time');        // 冷冻时间，精确到日
      $table->bigint('reopen_time');        // 解冻时间
      // 统计
      $table->bigint('count_visits');
      $table->bigint('count_likes');
      $table->bigint('count_cards');
      return $table;
  });
