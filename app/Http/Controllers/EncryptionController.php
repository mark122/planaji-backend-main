<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EncryptionController extends Controller
{
    public function encrypt()
    {
         $encrypted = Crypt::encryptString('v;Un=j5496UI');
         print_r($encrypted);
    }
}
