<?php

namespace App\Http\Controllers\Api\Participant;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\Participant\Controller;

class ParticipantsController extends Controller
{

    private $connection;
    private $plan_manager_subscription_id;

    public function __construct()
    {
        $user = $this->authUser();
        if(empty($user->connection)){
            return false;
        }else{

            $listTest = DB::connection($this->connection)->table('participants')
            ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'participants.planmanager_subscriptions_id')
            ->where('participants.id','=',$user->id)
            ->whereNull('participants.deleted_at')
            ->where('participants.planmanager_subscriptions_id','=',$user->planmanager_subscriptions_id)->get()->first();

            $this->connection = $user->connection;
            $this->plan_manager_subscription_id = $user->planmanager_subscriptions_id;
        }
    }


    public function list(Request $request){
        $user = $this->authUser();
        // if($request->app_name == "planaji") {

        //     $listTest = DB::connection($this->connection)->table('participants')
        //     ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'participants.planmanager_subscriptions_id')
        //     ->whereNull('participants.deleted_at')
        //     ->where('participants.id','=',$user->id)
        //     ->where('participants.planmanager_subscriptions_id','=',$user->planmanager_subscriptions_id)->get()->first();

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
        //     $list = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('planmanager_subscriptions_id','=',$this->plan_manager_subscription_id)->get();
            
        // }else {
            $list = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('planmanager_subscriptions_id','=',$user->planmanager_subscriptions_id)->get();
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


    public function details(Request $request, $id){
     
        $user = $this->authUser();

        // if($request->app_name == "planaji") {
        //     if($user->original['has_error']){
        //         $response = array(
        //             'settings'=>[
        //                 "status" => 0,
        //                 "message" => $user->original['msg']
        //             ],
        //             'data'=> []
        //         );
        //         return response()->json($response,401);
        //     }
        //     $list = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('id','=',$id)->where('planmanager_subscriptions_id','=',$this->plan_manager_subscription_id)->get()->first();

        // } else {
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
            $list = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('id','=',$id)->where('planmanager_subscriptions_id','=',$user->planmanager_subscriptions_id)->get()->first();
        //}
        if(empty($list)){
            $response = array(
                'settings'=>[
                    "status" => 1,
                    "message" => "No data found"
                ],
                'data'=> [$user->plan_manager_subscription_id,$id]
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


    /**Invoice List */
    public function invoiceList(Request $request)
    {
        //if($request->app_name == "plan_on_track")
        //{
            $user = $this->authUser();
            $list = DB::table('invoices')
            ->select('invoices.*', 'participants.ndis_number', 'invoice_details.amount')
            ->join('participants', 'participants.id', '=', 'invoices.participant_id')
            ->join('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->whereNull('invoices.deleted_at')
            ->where('participant_id', '=', $user->id)->get();

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
        //}
    }
}
