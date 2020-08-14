<?php

namespace App\Test;
use App\Test\CallApi;
use App\Test\Commands\GetMe;
use App\Test\Commands\Help;
use App\Test\Commands\Start;

class Telegram {
    protected $baseUrl = 'https://api.telegram.org/bot';

    public function __construct() {
        $this->TOKEN = '1267494595:AAGFKKPCU2Ui1oQOalBWHJ3WjfAAJGG4f30';
        $this->request = new CallApi();
        $this->lastUpdateId = 0;
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
//        dd($this->commands['/start'][0]);
    }

    private function urlMaker($path) {
        return $this->baseUrl . $this->TOKEN . $path;
    }

    private function getUpdates() {
//        $updates = $this->request->callApi('GET', $this->urlMaker('/getUpdates'), false);
        $updates = $this->request->callApi('GET', $this->urlMaker('/getUpdates'), [
            'offset' => $this->lastUpdateId,
            'limit' => 10000
        ]);
        return $updates;
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

        foreach($updates->result as &$update) {
            if(isset($this->commands[$update->message->text][1])) {
                $this->commands[$update->message->text][1]($update);
                $update_id = $update->update_id;
//                $sql = "INSERT INTO updates (update_id) VALUE ($update_id)";
            } else {
                $this->sendMessage($update->message->text . ' is not supported yet!', $update->message->chat->id);
            };
//            if($update->update_id > $this->lastUpdateId) {
//                $this->lastUpdateId = $update->update_id;
//            }
//            if($update_id > $this->lastUpdateId) {
//                $this->lastUpdateId = $update->update_id;
//            }
        }
    }
}
