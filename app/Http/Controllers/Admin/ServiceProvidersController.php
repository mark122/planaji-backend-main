<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ServiceProviders;
use Yajra\DataTables\DataTables;
use App\ProviderTypes;
use Illuminate\Support\Facades\DB;

// use Yajra\DataTables\DataTables as DataTablesDataTables;


class ServiceProvidersController extends Controller
{

    private $connection = NULL;
    private $ServiceProviders = NULL;
    public function __construct()
    {
        $this->ServiceProviders = new ServiceProviders;
        $this->ServiceProviders->connection = $this->connection;

        $this->middleware(function ($request, $next) {
            $this->connection = auth()->user()['connection']; // returns user
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providertypes = ProviderTypes::all();
        return view('admin.serviceproviders', compact('providertypes'));
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
        if ($request->ajax()) {


            $data = DB::connection($this->connection)->table('service_providers')
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
            service_providers.abn'
                )
                ->where('planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)->whereNull('deleted_at')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);


            // $data = ServiceProviders::latest()->get();
            // return Datatables::of($data)

            //     ->addIndexColumn()

            //     ->make(true);
        }

        return view('admin.serviceproviders');
    }

    public function saverecord(Request $request)
    {

        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

        $input = $request->all();
        if ($request->ajax()) {
            $isExist = DB::connection($this->connection)->table('service_providers')->whereNull('deleted_at')->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->where('email', '=', $request->input('email'))->where('id', '!=', $request->serviceprovider_id)->exists();
            if ($isExist) {
                return response()->json(['success' => true, 'created' => false, 'msg' => 'Record already exists!']);
                return false; // added one stopper to make sure, won't go through. since we don't use try and catch approach.
            }

            if ($this->connection != 'plan_on_track') {
                $isExist = DB::connection($this->connection)->table('service_providers')->whereNull('deleted_at')->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->where('abn', '=', $request->abn)->where('id', '!=', $request->serviceprovider_id)->exists();
                if ($isExist) { // added if statement to avoid duplication ABN Number in each plan manager data.
                    return response()->json(['success' => true, 'created' => false, 'msg' => 'Record with the same ABN already exists!']);
                    return false; // added one stopper to make sure, won't go through. since we don't use try and catch approach.
                }
            }

            $this->ServiceProviders->connection = $this->connection;
            $this->ServiceProviders->updateOrCreate(
                ['id' => $request->serviceprovider_id],
                [
                    'planmanager_subscriptions_id' => $request->planmanager_subscriptions_id,
                    'provider_type_id' => $request->provider_type_id,
                    'firstname' => $request->firstname,
                    'lastname' => "",
                    'mobile' => $request->mobile,
                    'address1' => $request->address1,
                    'address2' => $request->address2,
                    'state' => $request->state,
                    'postcode' => $request->postcode,
                    'email' => ($request->email == null) ? '' : $request->email,
                    'abn' => $request->abn
                ]
            );

            return response()->json(['success' => 'Record saved successfully.']);
        }
    }

    public function editrecord($id)
    {
        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

        $provider = DB::connection($this->connection)->table('service_providers')->where('id', '=', $id)->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->whereNull('deleted_at')->get()->first();
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
            $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

            // DB::connection($this->connection)->table('service_providers')->where('id','=',$id)->where('planmanager_subscriptions_id','=',$plan_manager_subscription_id)->delete();

            DB::connection($this->connection)->table('service_providers')
                ->where('id', $id)
                ->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)
                ->update(['deleted_at' => now()]);

            return response()->json(['success' => 'Record deleted successfully.']);
        }
    }
}
