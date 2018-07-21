<?php

  migration::table('cards', function ($table) {
      $table->bigint('id');
      $table->bigint('weight');
      $table->bigint('story_id');
      $table->text('openid');
      $table->longtext('content');
      $table->text('img');
      $table->int('activate');
      return $table;
  });
