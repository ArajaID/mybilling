<?php

use App\Jobs\GenerateTagihan;
use App\Jobs\CekBerlakuPromoJob;
use App\Jobs\IsolirPelangganJob;
use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();

Schedule::command('backup:run --only-db')->weekly();

Schedule::job(new GenerateTagihan)
->monthlyOn(1, '00:05');

Schedule::job(new IsolirPelangganJob)
->monthlyOn(22, '23:59');

Schedule::job(new CekBerlakuPromoJob)->daily();

Schedule::call('App\Http\Controllers\NotifikasiController@sendTelegram')
->monthlyOn(15, '06:00');