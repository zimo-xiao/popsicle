<?php

  migration::table('stories', function ($table) {
      $table->bigint('id');
      $table->text('title');
      $table->text('pinyin');
      $table->text('color');
      $table->text('tags');                 // 用"/"分割tag关键词
      $table->int('activate');              // 0未激活、1激活
      $table->bigint('create_time');        // 批准时间(同意后update)
      // 统计
      $table->bigint('count_visits');
      $table->bigint('count_likes');
      $table->bigint('count_cards');
      return $table;
  });
