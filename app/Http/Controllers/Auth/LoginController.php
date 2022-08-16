<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {

        logout as performLogout;
    }


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function index(Request $request)
    {
        $host = $request->getHttpHost();

        if (Str::contains($host, 'planontrack')) {

            return view('auth.enterpriselogin4', compact('host'));
        } else {

            return view('auth.login', compact('host'));
        }
    }

    public function enterpriselogin()
    {
        return view('auth.enterpriselogin');
    }
    public function enterpriselogin2()
    {
        return view('auth.enterpriselogin2');
    }
    public function enterpriselogin3()
    {
        return view('auth.enterpriselogin3');
    }

    public function enterpriselogin4()
    {
        return view('auth.enterpriselogin4');
    }

    public function logout(Request $request)
    {

        $this->performLogout($request);

        return redirect()->route('login');
        
    }
}
