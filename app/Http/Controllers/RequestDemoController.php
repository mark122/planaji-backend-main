<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RequestDemoController extends Controller
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
        return view('requestdemo');
    }
    public function data(Request $request)
    {

        $this->validate(request(), [
            'firstname' => 'required',
            'email' => 'required|email',
            'company' => 'required',
            'website' => 'required',
            'contact' => 'required',
        ]);
            $details = [
                'firstname' => $request->get('fname'),
                'company' => $request->get('company'),
                'email' => $request->get('email'),
                'website' => $request->get('website'),
                'contact' => $request->get('contact'),
            ];
            
            Mail::to('sales@planaji.com')->send(new \App\Mail\AppMails($details));
            return redirect()->back()->withSuccess('Mail Sent');
    }
    public function message(Request $request)
    {
            $details = [
                'fname' => $request->get('fname'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'message' => $request->get('message'),
            ];

            Mail::to('sales@planaji.com')->send(new \App\Mail\SendMessageMail($details));
            return redirect()->back()->withSuccess('Thank you for your message. Somebody from Planaji will contact you ASAP.');
    }
  
}
