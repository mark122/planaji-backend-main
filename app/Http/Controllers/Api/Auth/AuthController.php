<?php

namespace App\Http\Controllers\Api\Auth;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        $creds = $request->only(['email', 'password']);

        if(!$token = auth('api')->attempt($creds)){
            $response = array([
                'settings'=>[
                    "status" => 0,
                    "message" => "Incorrect Email/Password"
                ]
            ]);
            return response()->json($response,401);
        }


        if(!$token = auth('api')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'Participant'])){
            $response = array([
                'settings'=>[
                    "status" => 0,
                    "message" => "Participant Login not found"
                ]
            ]);
            return response()->json($response,401);
        }

        $user = $this->authUser();

        $participant_details = DB::connection($this->connection)->table('participants')
        ->select(DB::raw("firstname as name, email, planmanager_subscriptions_id "))
        ->where('participants.id', '=', $user-> table_id)->get();


        $response = array(
            'settings'=>[
                "status" => 1,
                "message" => "User is logged in"
            ],
            'data'=> [
                'name'=> $participant_details->name,
                'email'=> $participant_details->email,
                'role'=>$user->role,
                'plan_manager_subscription_id' => $participant_details->plan_manager_subscription_id,
                'profile_image'=>$user->profile_image,
                'connection'=>$user->connection,
                'token'=>$token,
                'changed_password' => ($user->changed_password == 1) ? 1 : 0
            ]
        );

        return response()->json($response);

    }


    public function refresh(){

        try{
            $newToken = auth('api')->refresh();

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error'=> $e->getMessage()]);
        }

        return response()->json(['token'=>$newToken]);

    }

}
