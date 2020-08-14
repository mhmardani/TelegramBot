<?php

namespace App\Http\Controllers;

use App\Update;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    public function index() {
        $updates = Update::all();
    }
}
