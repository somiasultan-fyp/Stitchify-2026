<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('tailors:reset-slots')
    ->monthlyOn(1, '00:00')
    ->withoutOverlapping();

Schedule::command('orders:remind-pending')
    ->hourly();    

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
