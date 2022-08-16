<?php

namespace App\Http\Controllers\Api\Auth;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanManagerController extends Controller
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
        $user = NULL;

        try {
            $user = auth('api')->userOrFail();
        }catch (TokenInvalidException $e) {
            return response()->json(['has_error'=>true, 'msg' => 'Invalid token'],401);
        }
        catch (TokenExpiredException $e) {   
            return response()->json(['has_error'=>true, 'msg' => 'Token has Expired'],401);
        }
        catch (JWTException $e) {   
            return response()->json(['has_error'=>true, 'msg' => 'Token not parsed'],401);
        }

        if(empty($user)){
            return false;
        }

        $response = array(
            'settings'=>[
                "status" => 1,
                "message" => "User is logged in"
            ],
            'data'=> [
                'name'=> $user->name,
                'email'=> $user->email,
                'role'=>$user->role,
                'plan_manager_subscription_id' => $user->plan_manager_subscription_id,
                'profile_image'=>$user->profile_image,
                'connection'=>$user->connection,
                'token'=>$token 
            ]
        );

        return response()->json($response);

    }


    public function refresh(){

        try{
            $newToken = auth('planmanagers')->refresh();

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error'=> $e->getMessage()]);
        }

        return response()->json(['token'=>$newToken]);

    }

}
