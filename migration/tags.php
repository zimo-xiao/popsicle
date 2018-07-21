<?php

  migration::table('tegs', function ($table) {
      $table->text('openid');
      $table->text('tag');
      $table->bigint('weight');
      return $table;
  });
