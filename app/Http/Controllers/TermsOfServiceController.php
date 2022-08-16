<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsOfServiceController extends Controller
{
    public function index()
    {
        auth()->logout();
        return view('termsofservice');
    }
}
