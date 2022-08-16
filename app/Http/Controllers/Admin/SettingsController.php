<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;
use Exception;
use App\User;
use Validator;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user_data= auth()->user();
        return view('admin.settings', compact('user_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function save(Request $request){

        try {
            $user_data= auth()->user();
            $row = DB::table('users')->where('id', '=',$user_data->id)->first();

            $validator = Validator::make($request -> all(),[
                'new_password' => 'required|string|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&.]/'
            ]);

            if(empty($request->old_password) || empty($request->new_password) || empty($request->retype_new_password)){
                throw new \Exception("Missing field/s, please try again");
            }
            if(!password_verify($request->old_password, $row->password)){
                throw new \Exception("Old password doesn't match, please try again");
            }
            if($request->new_password!=$request->retype_new_password){
                throw new \Exception("Re-try new password doesn't match, please try again");
            }
            if($request->old_password == $request->new_password){
                throw new \Exception("Old Password and new password cannot be same");
            }
            if ($validator -> fails()){
                throw new \Exception("The password format is invalid, please check the password requirement.");
            }
    
    

            User::updateOrCreate(
                ['id' => $row->id],
                [
                    'password' => Hash::make($request->new_password),
                ]
            );

            return response()->json(['has_error'=>false, 'message' => 'Password changed successfully.']);

        }
        catch (exception $exception) {
            return response()->json(['has_error'=>true,'message' => $exception->getMessage()]);
        }
    }

}
