<?php

  migration::table('visits', function ($table) {
      $table->text('ip');
      $table->int('port');
      $table->text('agent');
      $table->text('service');
      return $table;
  });
