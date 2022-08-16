<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\Controller;

class PlansController extends Controller
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
            $this->plan_manager_subscription_id = $user->plan_manager_subscription_id;
        }
    }

    public function list($participant_id){
     
        $user = $this->authUser();

        
        if($user->original['has_error']){
            $response = array(
                'settings'=>[
                    "status" => 0,
                    "message" => "has_error"
                ]
            );
            return response()->json($response,401);
        }


        $list = DB::connection($this->connection)->table('plans')->where('participant_id','=',$participant_id)->get();
        if(empty(count($list))){
            $response = array(
                'settings'=>[
                    "status" => 1,
                    "message" => "No data found"
                ],
                'data'=> [
                    'message' => "No data found"
                ]
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


    public function details($participant_id, $plan_id){
     
        $user = $this->authUser();

        if($user->original['has_error']){
            $response = array(
                'settings'=>[
                    "status" => 0,
                    "message" => "has_error"
                ]
            );
            return response()->json($response,401);
        }

        $list = DB::connection($this->connection)->table('plans')
        ->selectRaw('
            plan_details.id,
            plan_details.category_budget,
            plan_details.has_stated_item,
            plan_details.details,
            plan_details.support_payment,
            plan_details.has_quarantine_fund

            ')
        ->join('plan_details', 'plan_details.plan_id', '=', 'plans.id')   
        ->where('plans.participant_id','=',$participant_id)
        ->where('plan_details.plan_id','=',$plan_id)->get();
        if(empty(count($list))){
            $response = array(
                'settings'=>[
                    "status" => 1,
                    "message" => "No data found"
                ],
                'data'=> [
                    'message' => "No data found"
                ]
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
