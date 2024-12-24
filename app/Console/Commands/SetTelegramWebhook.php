<?php

namespace App\Console\Commands;

use Telegram\Bot\Api;
use Illuminate\Console\Command;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Set Telegram webhook URL';

    public function handle()
    { 
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $url = env('APP_URL') . '/telegram/webhook'; // Ganti APP_URL sesuai URL server Anda

        $telegram->setWebhook(['url' => $url]);

        $this->info('Webhook berhasil diset: ' . $url);
    }
}
