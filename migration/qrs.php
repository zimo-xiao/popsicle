<?php

  migration::table('qrs', function ($table) {
      $table->text('ticket');
      $table->text('service');
      $table->longtext('return_data');
      return $table;
  });
