<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Auth;
use Exception;
use App\User;
use App\PlanManager;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $connection = NULL;
    private $PlanManager = NULL;

    public function __construct()
    {
        //V2.1 
        $this->PlanManager = new PlanManager;
        $this->PlanManager->connection = $this->connection;

        $this->middleware(function ($request, $next) {
            $this->connection = auth()->user()['connection'];
            return $next($request);
        });
    }

    public function index()
    {   
        $this->PlanManager->connection = $this->connection;
        $user_data= auth()->user();

        $subscriptionDetails = DB::connection($this->connection)->table('users')
        ->selectRaw(
            "users.*, 
            subscriptions.*, 
            planmanager_subscriptions.*,
            plan_managers.qbname"
        )
        ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'users.plan_manager_subscription_id')
        ->leftJoin('subscriptions', 'subscriptions.id', '=', 'planmanager_subscriptions.subscription_id')
        ->leftJoin('plan_managers', 'plan_managers.id', '=', 'planmanager_subscriptions.plan_manager_id')
        ->where('users.id', '=', $user_data->id)
        ->get();

        $accountData = $subscriptionDetails[0];
        return view('admin.account', compact('accountData'));
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

        $this->PlanManager->connection = $this->connection;
        try {
            $user_data= auth()->user();
            $row = DB::table('users')->where('id', '=',$user_data->id)->first();

            $plan_manager_subscription = DB::connection($this->connection)->table('planmanager_subscriptions')->where('id', '=',$row->plan_manager_subscription_id)->first();

            $planmanager = $this->PlanManager->updateOrCreate(
                ['id' => $plan_manager_subscription->plan_manager_id],
                [
                    'qbname' => $request->qbname
                ]
            );

            return response()->json(['has_error'=>false, 'message' => 'Changes saved successfully.']);

        }
        catch (exception $exception) {
            return response()->json(['has_error'=>true,'message' => $exception->getMessage()]);
        }
    }
}
