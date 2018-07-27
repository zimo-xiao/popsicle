<?php

  migration::table('tags', function ($table) {
      $table->text('openid');
      $table->text('tag');
      $table->bigint('weight');
      return $table;
  });
