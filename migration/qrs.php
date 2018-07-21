<?php

  migration::table('qrs', function ($table) {
      $table->text('ticket');
      $table->text('service');
      return $table;
  });
