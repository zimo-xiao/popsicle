<?php

  migration::table('likes', function ($table) {
      $table->text('ip');
      $table->text('agent');
      $table->bigint('card_id');
      return $table;
  });
