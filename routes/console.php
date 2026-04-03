<?php

use App\Console\Commands\MarkForgotCheckout;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(MarkForgotCheckout::class)
    ->dailyAt('17:00')
    ->timezone('Asia/Jakarta')
    ->onOneServer();
