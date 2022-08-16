<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use DB;
use App\User;


class ForgotPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        auth()->logout();
        return view('forgotpassword');
    }

    public function data(Request $request)
    {

        $this->validate(request(), [
            'email' => 'required|email'
        ]);

        $row = DB::table('users')->where('email', '=',$request->get('email'))->first();

        if(!empty($row)){

            if($request->get('email')!=$request->get('email')){
                throw new \Exception("Re-try new password doesn't match, please try again");
            }
            // var_dump($row->id);

            $password_token = Str::random(32);

            User::updateOrCreate(
                ['id' => $row->id],
                [
                    'changed_password' => 0,
                    'password_token' => $password_token
                ]
            );

            $details = [
                'email' => $request->get('email'),
                'password_token' => $password_token
            ];


            Mail::to($request->get('email'))->send(new \App\Mail\ForgotPasswordMail($details));
            return redirect()->back()->withSuccess('Mail Sent');

        }else{
            return redirect()->back()->withErrors('Email not found');
        }



        

    }

    // public function message(Request $request)
    // {
    //         $details = [
    //             'fname' => $request->get('fname'),
    //             'email' => $request->get('email'),
    //             'phone' => $request->get('phone'),
    //             'message' => $request->get('message'),
    //         ];

    //         Mail::to('abdahad9@gmail.com')->send(new \App\Mail\SendMessageMail($details));
    //         return redirect()->back()->withSuccess('Thank you for your message. Somebody from Planaji will contact you ASAP.');
    // }
  
}
