<?php

  migration::table('schedules', function ($table) {
      $table->int('time');
      $table->text('text');
      $table->text('url');
      return $table;
  });
