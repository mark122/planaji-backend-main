<?php

namespace App\Http\Controllers\Api\Participant;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\Participant\Controller;

class ServiceProvidersController extends Controller
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
        //     if($user->original != null) {
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
        //     $list = DB::connection($this->connection)->table('service_providers')
        //                 ->join('provider_types', 'provider_types.id', '=', 'service_providers.provider_type_id')    
        //                 ->selectRaw(
        //                 'service_providers.id, 
        //                 provider_types.typename,
        //                 service_providers.firstname, 
        //                 service_providers.lastname, 
        //                 service_providers.mobile, 
        //                 service_providers.address1, 
        //                 service_providers.address2, 
        //                 service_providers.postcode, 
        //                 service_providers.email, 
        //                 service_providers.abn')
        //                 ->whereNull('service_providers.deleted_at')
        //                 ->where('planmanager_subscriptions_id','=',$this->plan_manager_subscription_id)->get();
        // }else{
            if($user->original != null) {
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
            $list = DB::connection($this->connection)->table('service_providers')
                        ->join('provider_types', 'provider_types.id', '=', 'service_providers.provider_type_id')    
                        ->selectRaw(
                        'service_providers.id, 
                        provider_types.typename,
                        service_providers.firstname, 
                        service_providers.lastname, 
                        service_providers.mobile, 
                        service_providers.address1, 
                        service_providers.address2, 
                        service_providers.postcode, 
                        service_providers.email, 
                        service_providers.abn')
                        ->whereNull('service_providers.deleted_at')
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
