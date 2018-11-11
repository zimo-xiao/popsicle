<?php

  migration::table('noti_pool', function ($table) {
      $table->int('time');
      $table->text('openid');
      $table->text('tag');
      return $table;
  });
