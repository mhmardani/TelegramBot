<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $table = 'updates';
    protected $fillable = ['update_id'];
}

