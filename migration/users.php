<?php

  migration::table('users', function ($table) {
      $table->text('openid');
      $table->text('name');
      $table->int('sex');
      $table->text('headimg');
      return $table;
  });
