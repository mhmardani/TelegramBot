<?php

namespace App\Test\Commands;
use App\Test\Telegram;

class Start extends Telegram {

    public static $description = 'start description';
    public function handle($update) {
        $this->sendMessage('this message was sent from /start command', $update->message->chat->id);
    }
}
