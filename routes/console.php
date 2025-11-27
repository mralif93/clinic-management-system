<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Generate recurring tasks daily at midnight
Schedule::command('todos:generate-recurring')
    ->daily()
    ->at('00:00')
    ->timezone('Asia/Kuala_Lumpur');

