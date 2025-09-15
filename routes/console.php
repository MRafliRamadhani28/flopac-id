<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule untuk safety stock calculation - jalan setiap hari jam 2 pagi
Schedule::command('safety-stock:calculate')->dailyAt('02:00');