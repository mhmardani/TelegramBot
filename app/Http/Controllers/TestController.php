<?php

namespace App\Http\Controllers;

use App\Test\CallApi;
use App\Test\Telegram;

class TestController extends Controller
{
    public function index() {
//        return Test\CallApi::callApi('GET', 'https://api.github.com/users', false);
        $request = new CallApi();
        return $request->callApi('GET', 'https://reqres.in/api/users', false);

    }

    public function getMe() {
        $tel = new Telegram();
        $me = $tel->getMe();
        return $me;
    }

    public function getUpdates(){
        $tel = new Telegram();
        $tel->updatesHandler();
        return 'ok';
    }
}

