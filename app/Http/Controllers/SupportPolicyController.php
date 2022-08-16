<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportPolicyController extends Controller
{
    public function index()
    {
        auth()->logout();
        return view('supportpolicy');
    }
}

