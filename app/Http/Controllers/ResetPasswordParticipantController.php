<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Participants;

class ResetPasswordParticipantController extends Controller
{

    private $connection = NULL;
    private $Participants = NULL;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Participants = new Participants;
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
        $app = $request->input('app');
        $id = $request->input('id');

        $this->connection = $app;
        
        $connectionlist = config('database.connections');

        if(empty(@$connectionlist[$app])){
            return view('resetpasswordparticipantexpired');
            return false;
        }

        $row = DB::connection($this->connection)->table('participants')->where('id', '=', $id)->where('password_token', '=',$get_token )->first();

        if(empty($row)){
            // echo json_encode(array('has_error'=>true, 'message'=>'Ops something went wrong!'));
            return view('resetpasswordparticipantexpired');

            return false;
        }
        if(!(boolean)$row->changed_password){
            return view('resetpasswordparticipant', compact('row','get_token','app','id'));
        }else{
            return view('resetpasswordparticipantexpired');
            return false;
        }  
        
    }

    public function success(Request $request){
        auth()->logout();
        $get_token = $request->input('get_token');
        $app = $request->input('app');
        $id = $request->input('id');

        $connectionlist = config('database.connections');
   
        if(empty(@$connectionlist[$app])){
            return view('resetpasswordparticipantexpired');
            return false;
        }
        // var_dump($app);
        // return false;
        $this->connection = $app;

        $row = DB::connection($this->connection)->table('participants')->where('id', '=',$id )->where('password_token', '=',$get_token )->first();
        // var_dump($row);
        if(empty($row)){
            return view('resetpasswordparticipantexpired');
            return false;
        }
        // var_dump($row);
        if((boolean)$row->changed_password){
            return view('resetpasswordparticipantsuccess', compact('row','get_token','app'));
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
        $app = $request->get('app');
        $id = $request->get('id');
        $connectionlist = config('database.connections');
        $this->connection = $app;

        $row = DB::connection($this->connection)->table('participants')->where('id', '=',$id )->where('email', '=',$request->get('email'))->first();

        // var_dump($row);
        if(empty(@$connectionlist[$app])){
            return view('resetpasswordparticipantexpired');
            return false;
        }
 
        if(!empty($row)){

            if($request->get('new_password')!=$request->get('retype_new_password')){
                return redirect()->back()->withErrors("Re-try new password doesn't match, please try again");
            }
            $this->Participants->connection = $app;
            $this->Participants->updateOrCreate(
                ['id' => $row->id],
                [
                    'changed_password' => 1,
                    'password' => Hash::make($request->get('new_password')),
                ]
            );
            $get_token = $request->get('get_token');
            // var_dump($row);
            return redirect('resetpasswordparticipant/success?get_token='.$get_token.'&app='.$app.'&id='.$id);

        
        }else{
            return redirect()->back()->withErrors('Ops! something went wrong!');
        } 
    }

}
