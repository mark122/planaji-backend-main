<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use DB;
use App\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
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
    public function index(Request $request)
    {
        auth()->logout();
        $get_token = $request->input('get_token');

        $row = DB::table('users')->where('password_token', '=',$get_token )->first();
        if(empty($row)){
            return view('resetpasswordparticipantexpired');
            return false;
        }

        if(!(boolean)$row->changed_password){
            return view('resetpassword', compact('row','get_token'));
        }else{
            return view('resetpasswordparticipantexpired');
            return false;
        }  
        
    }

    public function success(Request $request){
        auth()->logout();
        $get_token = $request->input('get_token');
        
        $row = DB::table('users')->where('password_token', '=',$get_token )->first();

        if(empty($row)){
            return view('resetpasswordparticipantexpired');
            return false;
        }

        if((boolean)$row->changed_password){
            return view('resetpasswordsuccess', compact('row','get_token'));
        }else{
            return view('resetpasswordparticipantexpired');
            return false;
        }   

    }

    public function reset(Request $request){
        
        $this->validate(request(), [
            'get_token'=> 'required',
            'email' => 'required|email',
            'new_password' => [
                'required',
                'string',
                'min:6',              // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&.]/', // must contain a special character
            ],
            'retype_new_password' => [
                'required',
                'string',
                'min:6',              // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&.]/', // must contain a special character
            ]
        ]);

        $row = DB::table('users')->where('email', '=',$request->get('email'))->first();

        if(!empty($row)){

            if($request->get('new_password')!=$request->get('retype_new_password')){
                return redirect()->back()->withErrors("Re-try new password doesn't match, please try again");
            }
        
            User::updateOrCreate(
                ['id' => $row->id],
                [
                    'changed_password' => 1,
                    'password' => Hash::make($request->get('new_password')),
                ]
            );
            $get_token = $request->get('get_token');

            return redirect('resetpassword/success?get_token='.$get_token);

        
        }else{
            return redirect()->back()->withErrors('Ops! something went wrong!');
        } 
    }

}
