<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SupportCoordinators;
use Illuminate\Support\Facades\DB;
use DataTables;

class SupportCoordinatorsController extends Controller
{

    private $connection = NULL;
    private $SupportCoordinators = NULL;
    public function __construct()
    {
        $this->SupportCoordinators = new SupportCoordinators;
        $this->SupportCoordinators->connection = $this->connection;

        $this->middleware(function ($request, $next) {
            $this->connection=auth()->user()['connection']; // returns user
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
        return view('admin.supportcoordinators');
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

            $data = DB::connection($this->connection)->table('support_coordinators')->where('planmanager_subscriptions_id',auth()->user()->plan_manager_subscription_id)->whereNull('deleted_at')->get();
            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editSupportCoordinator">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteSupportCoordinator">Delete</a>';

                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.supportcoordinators');
    }

    public function saverecord(Request $request)
    {
        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

        $input = $request->all();
        if ($request->ajax()) {
            $isExist = DB::connection($this->connection)->table('support_coordinators')->whereNull('deleted_at')->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->where('email', '=', $request->input('email'))->where('id', '!=', $request->supportcoordinator_id)->exists();
            if ($isExist) {
                return response()->json(['success' => true, 'created' => false, 'msg' => 'Record already exists!']);
                return false; // added one stopper to make sure, won't go through. since we don't use try and catch approach.
            }

        $this->SupportCoordinators->connection = $this->connection;
        $this->SupportCoordinators->updateOrCreate(
                ['id' => $request->supportcoordinator_id],
                [
                    'planmanager_subscriptions_id' => $request->planmanager_subscriptions_id,
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'office' => $request->office,
                    'mobile' => $request->mobile,
                    'email' => ($request->email == null) ? '': $request->email,
                    'address1' => $request->address1,
                    'address2' => $request->address2,
                    'state' => $request->state,
                    'postcode' => $request->postcode
                    // 'participant_ndis' => $request->participant_ndis
                ]
            );

            return response()->json(['success' => 'Record saved successfully.']);
        }
    }

    public function editrecord($id)
    {
        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;
        $coordinator = DB::connection($this->connection)->table('support_coordinators')->where('id','=',$id)->where('planmanager_subscriptions_id','=',$plan_manager_subscription_id)->whereNull('deleted_at')->get()->first();
        return response()->json($coordinator);
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
            // DB::connection($this->connection)->table('support_coordinators')->where('id','=',$id)->where('planmanager_subscriptions_id','=',$plan_manager_subscription_id)->delete();

            DB::connection($this->connection)->table('support_coordinators')
            ->where('id', $id)
            ->where('planmanager_subscriptions_id','=',$plan_manager_subscription_id)
            ->update(['deleted_at' => now()]);

            return response()->json(['success' => 'Record deleted successfully.']);
        }
    }
}
