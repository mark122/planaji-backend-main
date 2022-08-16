<?php

namespace App\Http\Controllers\Api\Participant;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\Participant\Controller;

class SupportCoordinatorsController extends Controller
{

    private $connection;
    private $plan_manager_subscription_id;

    public function __construct()
    {
        $user = $this->authUser();
        if(empty($user->connection)){
            return false;
        }else{
            $this->connection = $user->connection;
            $this->plan_manager_subscription_id = $user->planmanager_subscriptions_id;
        }
    }

    public function list(Request $request){
     
        $user = $this->authUser();
        // if($request->app_name == "planaji") {
        //     if(!empty($user->original)) {
        //         if($user->original['has_error']){
        //             $response = array(
        //                 'settings'=>[
        //                     "status" => 0,
        //                     "message" => $user->original['msg']
        //                 ],
        //                 'data'=> []
        //             );
        //             return response()->json($response,401);
        //         }
        //     }
        //     $list = DB::connection($this->connection)->table('support_coordinators')
        //     ->whereNull('support_coordinators.deleted_at')
        //     ->where('planmanager_subscriptions_id','=',$this->plan_manager_subscription_id)->get();
        // } else {
            if(!empty($user->original)) {
                if($user->original['has_error']){
                    $response = array(
                        'settings'=>[
                            "status" => 0,
                            "message" => $user->original['msg']
                        ],
                        'data'=> []
                    );
                    return response()->json($response,401);
                }        
            }
            $list = DB::connection($this->connection)->table('support_coordinators')
            ->whereNull('support_coordinators.deleted_at')
            ->where('planmanager_subscriptions_id','=',$user->planmanager_subscriptions_id)->get();
        //}
        


        
        if(empty(count($list))){
            $response = array(
                'settings'=>[
                    "status" => 1,
                    "message" => "No data found"
                ],
                'data'=> []
            );

        }else{

            $response = array(
                'settings'=>[
                    "status" => 1,
                    "message" => "Success"
                ],
                'data'=> $list
            );
        }

        return response()->json($response);

    }















   
}
