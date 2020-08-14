<?php

namespace App\Test\Commands;
use App\Test\Telegram;
use App\Test\Commands\Start;

class Help extends Telegram {
    public function handle($update) {
        $commands = [];
        foreach (array_keys($this->commands) as &$key){
            array_push($commands, $key. ' - ' .$this->commands[$key][0]);
        }
        $this->sendMessage('Here are our available commands:'. "\n" . join("\n", $commands), $update->message->chat->id);
    }
}
