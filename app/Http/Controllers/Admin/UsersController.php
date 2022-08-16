<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\User;
use App\PlanManager;
use App\PlanManagerSubscription;
use App\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// use Yajra\DataTables\DataTables as DataTablesDataTables;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $subscriptions = DB::table('subscriptions')
        ->get();


        return view('admin.users', compact('subscriptions'));
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

    public function loadrecords(Request $request)
    {
        $subscriptions = DB::table('subscriptions')
        ->get();

        if ($request->ajax()) {


            $data = DB::table('users') 
            ->selectRaw(
            'users.*, subscriptions.type as subscriptiontype')
            ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'users.plan_manager_subscription_id')
            ->leftJoin('subscriptions', 'subscriptions.id', '=', 'planmanager_subscriptions.subscription_id')
            ->where('users.id','!=',1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.users', compact('subscriptions'));
    }

    public function saverecord(Request $request)
    {

        $input = $request->all();
        if ($request->ajax()) {
            $isExist = DB::table('users')->where('email', '=', $request->input('email'))->exists();
            if ($isExist) {
                return response()->json(['success' => true, 'created' => false, 'msg' => 'Record already exists!']);
                return false; // added one stopper to make sure, won't go through. since we don't use try and catch approach.
            }

            $generated_password = Str::random(6);
            $defualt_password = Hash::make($generated_password);
            $password_token = Str::random(32);

            $planmanager = PlanManager::updateOrCreate(
                ['id' => 0],
                [
                    'name' => $request->name,
                    'invoice_email' => 0,// upon saving automatic set 0, since we don't provide required fields on UI.
                    'primary_contact_email' => $request->email
                ]
            );
            $planmanager_id = $planmanager->id;

            $planmanagersubscription = PlanManagerSubscription::updateOrCreate(
                ['id' => 0],
                [
                    'subscription_id' => $request->subscription_id,
                    'plan_manager_id' => $planmanager_id 
                ]
            );

            $planmanagersubscription_id = $planmanagersubscription->id;

            User::updateOrCreate(
                ['id' => $request->user_id],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'generated_password'=> $generated_password,
                    'password_token'=>$password_token,
                    'password' => $defualt_password,
                    'plan_manager_subscription_id' => $planmanagersubscription_id,
                    'status' => 'active',
                    'role' => 'Plan Manager',
                    'changed_password' => 0
                ]
            );

            return response()->json(['success' => 'Record saved successfully.']);
        }
    }

    public function editrecord($id)
    {
        $provider = ServiceProviders::find($id);
        return response()->json($provider);
    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Product  $product

     * @return \Illuminate\Http\Response

     */

    public function deleterecord(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            ServiceProviders::find($id)->delete();

            return response()->json(['success' => 'Record deleted successfully.']);
        }
    }
}
