<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $table = 'bots';
    protected $fillable = ['userName', 'lastUpdateId'];
    static function findByUserNameOrCreate($userName){
        $bots = Bot::where('userName', $userName)->get();
        if(count($bots) === 0){
            $newBot = new Bot();
            $newBot->userName = $userName;
            $newBot->lastUpdateId = 0;
            $newBot->save();
            return $newBot;
        }
        return $bots[0];
    }
}
