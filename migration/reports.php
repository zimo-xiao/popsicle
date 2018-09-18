<?php

  migration::table('reports', function ($table) {
      $table->bigint('report_time');
      $table->int('likes');
      $table->int('cards');
      $table->int('visits');
      return $table;
  });
