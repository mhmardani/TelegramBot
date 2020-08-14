<?php

namespace App\Test;
use App\Bot;
use App\Test\Commands\GetMe;
use App\Test\Commands\Help;
use App\Test\Commands\Start;

class Telegram {
    protected $baseUrl = 'https://api.telegram.org/bot';

    public function __construct($token = '', $username = '') {
        $this->token = ($token ?  $token : env('TELEGRAM_BOT_TOKEN'));
        $this->username = ($username ?  $username : env('TELEGRAM_BOT_USERNAME'));
        $this->request = new CallApi();
        $this->commands = array(
            '/start' => [
                Start::$description,
                function($update) {
                    $commands = new Start();
                    return $commands->handle($update);
                }],
            '/getMe' => [
                'Bot description',
                function($update) {
                    $commands = new GetMe();
                    return $commands->handle($update);
                }
            ],
            '/help' => [
                'Get a list of commands',
                function($update) {
                    $commands = new Help();
                    return $commands->handle($update);
                }
            ]
        );
    }

    private function urlMaker($path) {
        return $this->baseUrl . $this->token . $path;
    }

    private function getUpdates() {
        $bot = Bot::findByUserNameOrCreate($this->username);
        $updates = $this->request->callApi('GET', $this->urlMaker('/getUpdates'), [
            'offset' => $bot->lastUpdateId,
            'limit' => 10000
        ]);
        return $updates;
    }

    private function updateLastUpdateId($update_id){
        $bot = Bot::findByUserNameOrCreate($this->username);
        $bot->lastUpdateId = $update_id+1;
        $bot->update();
    }

    public function sendMessage($text = '', $chat_id = null) {
        $this->request->callApi('GET', $this->urlMaker('/sendMessage') , [
            'chat_id' => $chat_id,
            'text' => $text
        ]);;
    }

    public function getMe() {
        return $this->request->callApi('GET', $this->urlMaker('/getMe') , false);
    }

    public function updatesHandler() {
        $updates = $this->getUpdates();
        $lastUpdateId = 0;
        foreach($updates->result as &$update) {
            if(isset($this->commands[$update->message->text][1])) {
                $this->commands[$update->message->text][1]($update);
            } else {
                $this->sendMessage($update->message->text . ' is not supported yet!', $update->message->chat->id);
            }
            $lastUpdateId = $update->update_id;
        }
        $this->updateLastUpdateId($lastUpdateId);
    }


}
