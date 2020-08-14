<?php

namespace App\Test\Commands;
use App\Test\Telegram;

class GetMe extends Telegram {
    public function handle($update) {
        $this->sendMessage('this message was sent from /getMe command', $update->message->chat->id);
    }
}
