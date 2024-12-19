<?php

use App\Jobs\GenerateTagihan;
use App\Jobs\CekBerlakuPromoJob;
use App\Jobs\IsolirPelangganJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();

Schedule::command('backup:run --only-db')->weekly();

Schedule::job(new GenerateTagihan)
->monthlyOn(15, '00:01');

Schedule::job(new IsolirPelangganJob)
->monthlyOn(21, '23:59');

Schedule::job(new CekBerlakuPromoJob)->daily();

Schedule::call('App\Http\Controllers\NotifikasiController@sendTelegram')->dailyAt('07:00');