<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageUsController extends Controller
{  
    public function index()
    {
        auth()->logout();
        return view('messageus');
    }

}
