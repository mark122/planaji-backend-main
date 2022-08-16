<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Plans;
use App\SupportPurposes;
use App\OutcomeDomains;
use App\PlanSupportreference;
use App\PlanDetail;
use App\PlandetailsStateditems;
use App\Participants;
use App\ParticipantServiceproviders;
use App\ParticipantSupportcoordinators;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\PlanDocument;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use App\Mail\DisableAppAccess;



class ParticipantsController extends Controller
{

    private $connection = NULL;
    private $Participants = NULL;
    private $Plans = NULL;
    private $PlanDetail = NULL;
    private $ParticipantServiceProviders = NULL;
    private $ParticipantSupportcoordinators = NULL;
    private $PlandetailsStateditems = NULL;

    public function __construct()
    {
        $this->Participants = new Participants;
        $this->Plans = new Plans;
        $this->PlanDetail = new PlanDetail;
        $this->ParticipantServiceproviders = new ParticipantServiceproviders;
        $this->ParticipantSupportcoordinators = new ParticipantSupportcoordinators;
        $this->PlandetailsStateditems = new PlandetailsStateditems;
        $this->PlanDocument = new PlanDocument;

        $this->Participants->connection = $this->connection;
        $this->Plans->connection = $this->connection;
        $this->PlanDetail->connection = $this->connection;
        $this->ParticipantServiceproviders->connection = $this->connection;
        $this->ParticipantSupportcoordinators->connection = $this->connection;
        $this->PlandetailsStateditems->connection = $this->connection;
        $this->PlanDocument->connection = $this->connection;

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

        return view('admin.participants');
    }


    public function profile($id)
    {

        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

        $profile  = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('id', '=', $id)->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->get()->first();
        if (empty($profile)) { // if not found then redirect to starting page of the return value.
            return redirect('participants');
        }

        $plan_contract_generator = $this->plan_contract_generator();

        $plan_status = DB::connection($this->connection)->table('plan_status')->get();

        return view('admin.participantsprofile', compact('profile', 'plan_status', 'id', 'plan_contract_generator'));
    }

    public function plans($participant_id = NULL, $plan_id = NULL)
    {

        // var_dump($participant_id);
        // var_dump($plan_id);


        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

        $profile  = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('id', '=', $participant_id)->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->get()->first();
        if (empty($profile)) { // if not found then redirect to starting page of the return value.
            return redirect('participants');
        }

        // dd($profile);

        $support_purposes =  DB::connection($this->connection)->table('support_purposes')->get();

        $support_categories =  DB::connection($this->connection)->table('support_categories')->get();

        $stated_items =  DB::connection($this->connection)->table('stated_items')->get();

        $plan = DB::connection($this->connection)->table('plans')->whereNull('deleted_at')->where('id', '=', $plan_id)->get()->first();

        // ->where('status', '=', 'Active');

        $spent = DB::connection($this->connection)->table('plan_details')
            ->select(DB::raw("SUM(category_budget) as category_budget"))
            ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
            ->where('plans.participant_id', '=', $participant_id)
            ->where('plan_details.plan_id', '=', $plan_id)
            ->whereNull('plans.deleted_at')
            ->whereNull('plan_details.deleted_at')
            ->get();

        $totalInvoiceAmount = DB::connection($this->connection)->table('invoice_details')
            ->select(DB::raw("SUM(invoice_details.amount) as amount"))
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->where('invoices.participant_id', '=', $participant_id)
            ->whereNull('invoices.deleted_at')
            ->whereNull('invoice_details.deleted_at')
            ->get();


        //$newTotalAllocated = (float)$spent[0]->category_budget - (float)$totalInvoiceAmount[0]->amount;
        $newTotalAllocated = (float)$spent[0]->category_budget;


        // $plan_details = DB::connection($this->connection)->table('plan_details')
        // ->where('plan_details.plan_id','=',$plan_id)->get();

        $core_support_total_budget = DB::connection($this->connection)->table('plan_details')
            ->join('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
            ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
            ->join('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
            ->select(DB::raw("SUM(plan_details.category_budget) as total_budget"))
            ->where('plans.participant_id', '=', $participant_id)
            ->where('plan_details.plan_id', '=', $plan_id)
            ->where('support_purposes.id', '=', 1)
            ->whereNull('plans.deleted_at')
            ->whereNull('plan_details.deleted_at')
            ->get();


        $capital_total_budget = DB::connection($this->connection)->table('plan_details')
            ->join('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
            ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
            ->join('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
            ->select(DB::raw("SUM(plan_details.category_budget) as total_budget"))
            ->where('plans.participant_id', '=', $participant_id)
            ->where('plan_details.plan_id', '=', $plan_id)
            ->where('support_purposes.id', '=', 2)
            ->whereNull('plans.deleted_at')
            ->whereNull('plan_details.deleted_at')
            ->get();

        $capacity_building_total_budget = DB::connection($this->connection)->table('plan_details')
            ->join('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
            ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
            ->join('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
            ->select(DB::raw("SUM(plan_details.category_budget) as total_budget"))
            ->where('plans.participant_id', '=', $participant_id)
            ->where('plan_details.plan_id', '=', $plan_id)
            ->where('support_purposes.id', '=', 3)
            ->whereNull('plans.deleted_at')
            ->whereNull('plan_details.deleted_at')
            ->get();


        $core_spent = DB::connection($this->connection)->table('invoice_details')
            ->select(DB::raw("ROUND(SUM(invoice_details.amount), 2) as amount"))
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
            ->where('plan_supportreference.support_purposes_id', '=', 1)
            ->where('invoices.participant_id', '=', $participant_id)
            ->whereNull('invoices.deleted_at')
            ->whereNull('invoice_details.deleted_at')
            ->get();

        $capital_spent = DB::connection($this->connection)->table('invoice_details')
            ->select(DB::raw("ROUND(SUM(invoice_details.amount), 2) as amount"))
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
            ->where('plan_supportreference.support_purposes_id', '=', 2)
            ->where('invoices.participant_id', '=', $participant_id)
            ->whereNull('invoices.deleted_at')
            ->whereNull('invoice_details.deleted_at')
            ->get();

        $capacity_spent = DB::connection($this->connection)->table('invoice_details')
            ->select(DB::raw("ROUND(SUM(invoice_details.amount), 2) as amount"))
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
            ->where('plan_supportreference.support_purposes_id', '=', 3)
            ->where('invoices.participant_id', '=', $participant_id)
            ->whereNull('invoices.deleted_at')
            ->whereNull('invoice_details.deleted_at')
            ->get();

        $core_remaining = (float) $core_support_total_budget[0]->total_budget - (float) $core_spent[0]->amount;
        $capital_remaining = (float) $capital_total_budget[0]->total_budget - (float) $capital_spent[0]->amount;
        $capacity_remaining = (float) $capacity_building_total_budget[0]->total_budget - (float) $capacity_spent[0]->amount;

        // $core_spent = (!empty($core_spent)) ? $core_spent[0]->amount : 0;
        // $capital_spent = (!empty($capital_spent)) ? $capital_spent[0]->amount : 0;
        // $capacity_spent = (!empty($capacity_spent)) ? $capacity_spent[0]->amount : 0;

        if ($plan->status == "Active") {
            $this->Plans->connection = $this->connection;
            $this->Plans->updateOrCreate(
                ['id' => $plan_id],
                [
                    'total_delivered' => (!empty($totalInvoiceAmount[0]->amount) ? $totalInvoiceAmount[0]->amount : 0),
                    'total_allocated' => (!empty($newTotalAllocated) ? $newTotalAllocated : 0),
                    'core_budget' => (!empty($core_support_total_budget[0]->total_budget) ? $core_support_total_budget[0]->total_budget : 0),
                    'capital_budget' => (!empty($capital_total_budget[0]->total_budget) ? $capital_total_budget[0]->total_budget : 0),
                    'capacity_budget' => (!empty($capacity_building_total_budget[0]->total_budget) ? $capacity_building_total_budget[0]->total_budget : 0),
                    'core_remaining' => (!empty($core_remaining) ? $core_remaining : (!empty($core_support_total_budget[0]->total_budget) ? $core_support_total_budget[0]->total_budget : 0)),
                    'capital_remaining' => (!empty($capital_remaining) ? $capital_remaining : (!empty($capital_total_budget[0]->total_budget) ? $capital_total_budget[0]->total_budget : 0)),
                    'capacity_remaining' => (!empty($capacity_remaining) ? $capacity_remaining : (!empty($capacity_building_total_budget[0]->total_budget) ? $capacity_building_total_budget[0]->total_budget : 0))
                ]
            );

            $plan = DB::connection($this->connection)->table('plans')->whereNull('deleted_at')->where('id', '=', $plan_id)->get()->first();
            $totalClaimed = (float) $totalInvoiceAmount[0]->amount;
        } else {
            $this->Plans->connection = $this->connection;
            $this->Plans->updateOrCreate(
                ['id' => $plan_id],
                [
                    'total_delivered' => 0,
                    'total_allocated' => (!empty($spent[0]->category_budget) ? $spent[0]->category_budget : 0),
                    'core_budget' => (!empty($core_support_total_budget[0]->total_budget) ? $core_support_total_budget[0]->total_budget : 0),
                    'capacity_budget' => (!empty($capacity_building_total_budget[0]->total_budget) ? $capacity_building_total_budget[0]->total_budget : 0),
                    'capital_budget' => (!empty($capital_total_budget[0]->total_budget) ? $capital_total_budget[0]->total_budget : 0),
                    'core_remaining' => (!empty($core_remaining) ? $core_remaining : 0),
                    'capital_remaining' => (!empty($capital_remaining) ? $capital_remaining : 0),
                    'capacity_remaining' => (!empty($capacity_remaining) ? $capacity_remaining : 0)
                ]
            );

            $plan = DB::connection($this->connection)->table('plans')->whereNull('deleted_at')->where('id', '=', $plan_id)->get()->first();

            $totalClaimed = (float) 0;
        }


        $remaining_allocated = (float)$spent[0]->category_budget - (float)$totalClaimed;



        $service_providers = DB::connection($this->connection)->table('service_providers')
            ->join('provider_types', 'provider_types.id', '=', 'service_providers.provider_type_id')
            ->selectRaw(
                'service_providers.id, 
                provider_types.typename, 
                service_providers.firstname, 
                service_providers.lastname, 
                service_providers.mobile, 
                service_providers.email
            '
            )
            ->where('planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
            ->whereNull('deleted_at')->get();

        $support_coordinators = DB::connection($this->connection)->table('support_coordinators')
            ->selectRaw(
                'support_coordinators.id, 
                support_coordinators.firstname, 
                support_coordinators.lastname, 
                support_coordinators.mobile, 
                support_coordinators.email
            '
            )
            ->where('planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
            ->whereNull('deleted_at')->get();

        $plan_documents = DB::connection($this->connection)->table('plan_documents')
            ->where('plan_id', $plan_id)->orderBy('id', 'DESC')->whereNull('deleted_at')->get();

        return view('admin.participantsplan', compact(
            'profile',
            'support_purposes',
            'support_categories',
            'stated_items',
            'core_support_total_budget',
            'capital_total_budget',
            'capacity_building_total_budget',
            'service_providers',
            'support_coordinators',
            'plan',
            'spent',
            'remaining_allocated',
            'newTotalAllocated',
            'totalClaimed',
            'plan_id',
            'participant_id',
            'core_remaining',
            'capital_remaining',
            'capacity_remaining',
            'core_spent',
            'capital_spent',
            'capacity_spent',
            'plan_documents'
        ));
    }

    public function getoutcomedomains(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('plan_supportreference')
                ->join('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
                ->where('plan_supportreference.support_categories_id', '=', $request->support_categories_id)->get();
            return response()->json($data);
        }
    }

    public function getstateditems(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('ndis_pricingguides')
                ->where('ndis_pricingguides.support_category_number', '=', $request->support_categories_id)->get();
            return response()->json($data);
        }
    }

    public function getparticipantserviceprovider(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('participant_serviceproviders')
                ->join('service_providers', 'service_providers.id', '=', 'participant_serviceproviders.service_provider_id')
                ->where('participant_serviceproviders.plan_id', '=', $request->plan_id)
                ->whereNull('deleted_at')
                ->get();
            return response()->json($data);
        }
    }

    public function getserviceproviders(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('service_providers')
                //    ->join('provider_types', 'provider_types.id', '=', 'service_providers.provider_type_id')   
                ->where('planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                ->whereNull('deleted_at')
                ->get();
            // ->join('service_providers', 'service_providers.id', '=', 'participant_serviceproviders.service_provider_id')
            // ->where('participant_serviceproviders.plan_id', '=', $request->plan_id)->get();
            return response()->json($data);
        }
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

    public function editrecord($id)
    {
        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;
        $participant = DB::connection($this->connection)->table('participants')->where('id', '=', $id)
            ->whereNull('deleted_at')
            ->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->get()->first();
        return response()->json($participant);
    }





    public function saverecord(Request $request)
    {
        $input = $request->all();
        $review_date = new DateTime($request->ndis_plan_end_date);
        $review_date->modify('-1 month');
        $review_date = $review_date->format('Y-m-d');
        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;


        if ($request->ajax()) {
            if ((!is_numeric($request->ndis_number)) || !preg_match('/^(\d{9})$/', $request->ndis_number)) {
                return response()->json(['success' => true, 'created' => false, 'msg' => 'Invalid NDIS number. The NDIS number should contain at least 9 numeric digits.']); //UPDATE: ELABORATE THE NDIS NUMBER FORMAT - 2022-11-07
                //return false; // If Ndis Number is not 9 digit or not in number format.
            }
            $isExist = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->where('email', '=', $request->input('email'))->where('email', '!=', 'null')->where('id', '!=', $request->participant_id)->exists();
            // if (empty($request->participant_id) && $isExist) {
            if ($isExist) {
                return response()->json(['success' => true, 'created' => false, 'msg' => 'Email Address already exists!']);
                //return false; // added one stopper to make sure, won't go through. since we don't use try and catch approach.
            }

            $isExist = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->where('ndis_number', '=', $request->input('ndis_number'))->where('id', '!=', $request->participant_id)->exists(); // seperated checker to prevent ndis_number duplication upon saving the data.
            // if (empty($request->participant_id) && $isExist) {

            if ($isExist) {
                return response()->json(['success' => true, 'created' => false, 'msg' => 'NDIS number already exists!']);
                return false; // added one stopper to make sure, won't go through. since we don't use try and catch approach.
            }

            $this->Participants->connection = $this->connection;

            $response = $this->Participants->updateOrCreate(
                ['id' => $request->participant_id],
                [
                    'planmanager_subscriptions_id' => $plan_manager_subscription_id,
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'ndis_number' => $request->ndis_number,
                    'aboutme' => $request->aboutme,
                    'email' => $request->email,
                    'dateofbirth' => $request->dateofbirth,
                    'address1' => $request->address1,
                    'address2' => $request->address2,
                    'state' => $request->state,
                    'postcode' => $request->postcode,
                    'homenumber' => $request->homenumber,
                    'phonenumber' => $request->phonenumber,
                    'ndis_plan_start_date' => $request->ndis_plan_start_date,
                    'ndis_plan_review_date' => $review_date,
                    'ndis_plan_end_date' => $request->ndis_plan_end_date,
                    'short_term_goals' => $request->short_term_goals,
                    'long_term_goals' => $request->long_term_goals,
                    'status' => 'Active',
                    'app_access_enabled' => ($request->app_access_enabled == "on") ? 1 : 0
                ]
            );

            $this->runtime_mail_config($this->connection);
            //var_dump($this->runtime_mail_config($this->connection));
            if ($request->app_access_enabled != "on" && $request->email != null && !empty($request->participant_id)) { //UPDATE: FOR NEWLY ADDED AND IF SET OFF THE "Enable App Access" NO NEED TO SEND NOTIF.
                $details = [
                    'send_from' => ($this->connection == 'plan_on_track') ? 'planapp@planontrack.com.au' : 'support@planaji.com',
                    'name' => $request->firstname . ' ' . $request->lastname,
                    'app' => $this->connection,
                    'email' => $request->email
                ];

                $response = Mail::to($request->email)->send(new DisableAppAccess($details));
            }

            if ($request->app_access_enabled == "on" && $request->email != null) {
                $generated_password = Str::random(6);
                $default_password = Hash::make($generated_password);
                $password_token = Str::random(32);

                $id = (!empty($request->participant_id)) ? $request->participant_id : $response->id;

                $isPasswordChanged = DB::connection($this->connection)->table('participants')->whereNull('deleted_at')->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->where('id', '=', $id)->where('changed_password', '!=', 0)->exists();

                if (!$isPasswordChanged) {
                    $this->Participants->updateOrCreate(
                        ['id' => $id],
                        [
                            'changed_password' => 0,
                            'password_token' => $password_token,
                            'generated_password' => $generated_password,
                            'password' => $default_password,
                        ]
                    );
                }

                $this->runtime_mail_config($this->connection);
                //var_dump($this->runtime_mail_config($this->connection));
                $details = [
                    'send_from' => ($this->connection == 'plan_on_track') ? 'planapp@planontrack.com.au' : 'support@planaji.com',
                    'email' => $request->email,
                    'name' => $request->firstname . ' ' . $request->lastname,
                    'app' => $this->connection,
                    'id' => $id,
                    'password_token' => $password_token,
                    'generated_password' => $generated_password,
                    'changed_password' => $response->changed_password
                ];


                $response = Mail::to($request->email)->send(new \App\Mail\EnableAppAccess($details));
            }

            return response()->json(['success' => 'Record saved successfully.']);
        }
    }

    public function runtime_mail_config($connection){

        $get_email_configs = get_email_config($connection);

        //var_dump( $get_email_configs);

        if($get_email_configs)
            foreach($get_email_configs as $key=>$value){
                Config::set('mail.mailers.smtp.'.$key, $value);
            }
    }

    public function deleterecord(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            if (empty($id)) {
                return response()->json(['success' => false, 'msg' => 'Please select item']);
            }

            $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

            // DB::connection($this->connection)->table('participants')->where('id','=',$id)->where('planmanager_subscriptions_id','=',$plan_manager_subscription_id)->delete();

            DB::connection($this->connection)->table('participants')
                ->where('id', $id)
                ->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)
                ->update(['deleted_at' => now()]);

            return response()->json(['success' => 'Record deleted successfully.']);
        }
    }

    public function loadrecords(Request $request)
    {

        if ($request->ajax()) {

            $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;
            $data = DB::connection($this->connection)->table('participants')->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->whereNull('deleted_at')->orderBy('participants.id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.participants');
    }

    public function addinvoice($id)
    {

        $service_providers = DB::connection($this->connection)->table('service_providers')
            ->where('service_providers.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('deleted_at')
            ->get();

        $participants = DB::connection($this->connection)->table('participants')
            ->where('participants.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('deleted_at')
            ->get();

        $participant_detail = DB::connection($this->connection)->table('participants')
            ->whereNull('deleted_at')
            ->where('participants.id', '=', $id)
            ->first();

        $invoice_emails = DB::connection($this->connection)->table('invoice_emails')
            ->where('invoice_emails.plan_manager_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('deleted_at')
            ->get();

        $gst = DB::connection($this->connection)->table('gst')->get();

        $claim_type = DB::connection($this->connection)->table('claim_type')->get();

        $route = 'Add';

        $user_auth = $this->connection;




        return view('admin.invoicedetails', compact('user_auth', 'route', 'participants', 'service_providers', 'invoice_emails', 'gst', 'claim_type', 'participant_detail'));
    }

    public function loadrecordinvoices(Request $request)
    {

        if ($request->ajax()) {


            $data = DB::connection($this->connection)->table('invoices')
                ->selectRaw(
                    '
                invoices.id,
                participants.ndis_number,
                invoices.invoice_number,
                invoices.invoice_date,
                invoices.due_date,
                invoices.reference_number,
                service_providers.abn as service_provider_ABN,
                invoices.service_provider_acc_number,
                invoice_status.description as status,
                service_providers.firstname as service_provider_first_name,
                service_providers.lastname as service_provider_last_name,
                invoices.remarks,
                participants.firstname as participant_firstname,
                participants.lastname as participant_lastname,
                SUM(invoice_details.amount) as invoice_amt
                '
                )
                ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
                ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
                ->leftJoin('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
                ->leftJoin('invoice_status', 'invoice_status.id', '=', 'invoices.status')
                ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                ->where('invoices.participant_id', '=', $request->participant_id)
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->orderBy('invoices.id', 'desc')
                ->groupBy(
                    'invoices.id',
                    'participants.ndis_number',
                    'invoices.invoice_number',
                    'invoices.invoice_date',
                    'invoices.due_date',
                    'invoices.reference_number',
                    'service_providers.abn',
                    'invoices.service_provider_acc_number',
                    'invoices.status',
                    'service_providers.firstname',
                    'service_providers.lastname',
                    'invoices.remarks',
                    'participants.firstname',
                    'participants.lastname',
                    'invoice_status.description'
                )
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.participantsplan');
    }

    public function loadrecordserviceprovider(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('participant_serviceproviders')
                ->join('participants', 'participants.id', '=', 'participant_serviceproviders.participant_id')
                ->join('service_providers', 'service_providers.id', '=', 'participant_serviceproviders.service_provider_id')
                ->join('provider_types', 'provider_types.id', '=', 'service_providers.provider_type_id')
                ->selectRaw(
                    'participant_serviceproviders.id,
                    provider_types.typename,
                    service_providers.firstname,
                   service_providers.lastname,
                   service_providers.mobile,
                   service_providers.email'
                )
                ->where('participant_serviceproviders.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                ->where('participant_serviceproviders.participant_id', '=', $request->participant_id)
                ->where('participant_serviceproviders.plan_id', '=', $request->plan_id)
                ->whereNull('participant_serviceproviders.deleted_at')
                ->get();
            // ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.participantsplan');
    }

    public function deleterecordserviceprovider(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            if (empty($id)) {
                return response()->json(['success' => false, 'msg' => 'Please select item']);
            }


            // DB::connection($this->connection)->table('participant_serviceproviders')->where('id','=',$id)->delete();

            DB::connection($this->connection)->table('participant_serviceproviders')
                ->where('id', $id)
                ->update(['deleted_at' => now()]);

            return response()->json(['success' => 'Record deleted successfully.']);
        }
    }

    public function saverecordserviceprovider(Request $request)
    {

        $input = $request->all();
        if ($request->ajax()) {

            $isExist = DB::connection($this->connection)->table('participant_serviceproviders')->where([
                ['plan_id', '=', $request->input('plan_id')],
                ['participant_id', '=', $request->input('participant_id')],
                ['service_provider_id', '=', $request->input('serviceprovider_id')]
            ])->exists();

            if (empty($request->participantserviceprovider_id) && $isExist) {
                return response()->json(['success' => true, 'created' => false, 'msg' => 'Record already exists!']);
            }
            $this->ParticipantServiceproviders->connection = $this->connection;
            $this->ParticipantServiceproviders->updateOrCreate(
                ['id' => $request->participantserviceprovider_id],
                [
                    'plan_id' => $request->plan_id,
                    'participant_id' => $request->participant_id,
                    'service_provider_id' => $request->serviceprovider_id,
                    'planmanager_subscriptions_id' => $request->planmanager_subscriptions_id,
                    'plan_id' => $request->input('plan_id')
                ]
            );


            return response()->json(['success' => $request->planmanager_subscriptions_id]);
        }
    }

    public function loadrecordsupportcoordinator(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('participant_supportcoordinators')
                ->join('participants', 'participants.id', '=', 'participant_supportcoordinators.participant_id')
                ->join('support_coordinators', 'support_coordinators.id', '=', 'participant_supportcoordinators.support_coordinator_id')
                ->selectRaw(
                    'participant_supportcoordinators.id,
                    support_coordinators.firstname,
                    support_coordinators.lastname,
                    support_coordinators.mobile,
                    support_coordinators.email
                    '
                )
                // ->get();
                ->where('participant_supportcoordinators.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                ->where('participant_supportcoordinators.participant_id', '=', $request->participant_id)
                ->where('participant_supportcoordinators.plan_id', '=', $request->plan_id)
                ->whereNull('participant_supportcoordinators.deleted_at')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.participantsplan');
    }

    public function deleterecordsupportcoordinator(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            if (empty($id)) {
                return response()->json(['success' => false, 'msg' => 'Please select item']);
            }


            // DB::connection($this->connection)->table('participant_supportcoordinators')->where('id','=',$id)->delete();

            DB::connection($this->connection)->table('participant_supportcoordinators')
                ->where('id', $id)
                ->update(['deleted_at' => now()]);

            return response()->json(['success' => 'Record deleted successfully.']);
        }
    }

    public function saverecordsupportcoordinator(Request $request)
    {

        $input = $request->all();
        if ($request->ajax()) {

            $isExist = DB::connection($this->connection)->table('participant_supportcoordinators')->where([
                ['participant_id', '=', $request->input('participant_id')],
                ['support_coordinator_id', '=', $request->input('support_coordinator_id')]
            ])->exists();

            if (empty($request->participantsupport_coordinator_id) && $isExist) {
                return response()->json(['success' => true, 'created' => false, 'msg' => 'Record already exists!']);
            }
            $this->ParticipantSupportcoordinators->connection = $this->connection;
            $this->ParticipantSupportcoordinators->updateOrCreate(
                ['id' => $request->participantsupport_coordinator_id],
                [
                    'participant_id' => $request->participant_id,
                    'support_coordinator_id' => $request->support_coordinator_id,
                    'planmanager_subscriptions_id' => $request->planmanager_subscriptions_id,
                    'plan_id' => $request->input('plan_id')
                ]
            );


            return response()->json(['success' => 'Record saved successfully.', 'data' => $isExist]);
        }
    }

    public function saverecordplan(Request $request)
    {

        $input = $request->all();

        $review_date_plan = new DateTime($request->plan_date_end);
        $review_date_plan->modify('-1 month');
        $review_date_plan = $review_date_plan->format('Y-m-d');


        if ($request->ajax()) {

            // $isExist = DB::connection($this->connection)->table('plans')->where([
            //     ['plan_contract', '=', $request->plan_contract_no]
            // ])->exists();

            // if ($isExist) {
            //     return response()->json(['success' => true, 'created' => false, 'msg' => 'Record already exists!']);
            // }
            $plan_contract_no = ((bool)$request->plan_id) ? $request->plan_contract_no : $this->plan_contract_generator();
            // var_dump($plan_contract_no);
            $this->Plans->connection = $this->connection;

            $this->Plans->updateOrCreate(
                [
                    'id' => $request->plan_id,
                    'participant_id' => $request->participant_id
                ],
                [
                    'participant_id' => $request->participant_id,
                    'plan_contract' => $plan_contract_no,
                    'status' => $request->plan_status_id,
                    'plan_date_start' => $request->plan_date_start,
                    'plan_date_end' => $request->plan_date_end,
                    'plan_date_review' => $review_date_plan,
                    'total_funding' => $request->total_funding,
                    'total_allocated' => 0,
                    'total_remaining' => $request->total_funding,
                    'total_delivered' => 0,
                    'total_claimed' => 0,
                    'total_unclaimed' => 0,
                ]
            );


            return response()->json(['success' => 'Record saved successfully.']);
        }
    }

    public function getplan_contractno()
    {
        $plan_contract_generator = $this->plan_contract_generator();
        return response()->json(array('getnewcontactno' => $plan_contract_generator));
    }

    private function plan_contract_generator()
    {

        $get_last_row = DB::connection($this->connection)->table('plans')->get()->last();

        $get_id = 0;

        if (!empty($get_last_row)) {
            $get_id = $get_last_row->id;
        }

        $code = "PC";
        $lenghtofzero = 7;
        $increment_id = $get_id + 1;
        $zerofill = str_pad((string)$increment_id, $lenghtofzero, "0", STR_PAD_LEFT);

        return $code . $zerofill;
    }

    public function loadrecordplans(Request $request)
    {

        $participant_id = $request->get('participant_id');

        $plan = DB::connection($this->connection)->table('plans')->where('participant_id', '=', $participant_id)->where('status', '=', 'Active')->whereNull('deleted_at')->get()->first();
        // var_dump($plan);
        $planUpdate = DB::connection($this->connection)->table('plans')->where('participant_id', '=', $participant_id)->whereNull('deleted_at')->get();


        foreach ($planUpdate as $key => $plan_item) {

            $spent = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->where('plans.participant_id', '=', $participant_id)
                ->where('plan_details.plan_id', '=', $plan_item->id)
                ->whereNull('plans.deleted_at')
                ->whereNull('plan_details.deleted_at')
                ->get();

            $totalInvoiceAmount = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::raw("SUM(invoice_details.amount) as amount"))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where('invoices.participant_id', '=', $participant_id)
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->get();

            $totalClaimedAmount = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::raw("SUM(invoice_details.amount) as amount"))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where('invoices.participant_id', '=', $participant_id)
                ->where('invoices.status', '=', '6')
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->get();

            $totalUnclaimedAmount = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::raw("SUM(invoice_details.amount) as amount"))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where('invoices.participant_id', '=', $participant_id)
                ->where('invoices.status', '!=', '6')
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->get();


            // $newTotalAllocated = (float)$spent[0]->category_budget - (float)$totalInvoiceAmount[0]->amount;
            $newTotalAllocated = (float)$spent[0]->category_budget;



            if ($plan_item->status == "Active") {

                $this->Plans->connection = $this->connection;
                $this->Plans->updateOrCreate(
                    ['id' => $plan_item->id],
                    [
                        'total_delivered' => (!empty($totalInvoiceAmount[0]->amount) ? $totalInvoiceAmount[0]->amount : 0),
                        'total_allocated' => (!empty($newTotalAllocated) ? $newTotalAllocated : 0),
                        'total_claimed' => (!empty($totalClaimedAmount[0]->amount) ? $totalClaimedAmount[0]->amount : 0),
                        'total_unclaimed' => (!empty($totalUnclaimedAmount[0]->amount) ? $totalUnclaimedAmount[0]->amount : 0),
                        'total_remaining' => (float)$plan_item->total_funding - (float)$spent[0]->category_budget
                    ]
                );
            } else {
                $this->Plans->connection = $this->connection;
                $this->Plans->updateOrCreate(
                    ['id' => $plan_item->id],
                    [
                        'total_delivered' => 0,
                        'total_allocated' => (float)$spent[0]->category_budget,
                        'total_claimed' => 0,
                        'total_unclaimed' => 0,
                        'total_remaining' => (float)$plan_item->total_funding - (float)$spent[0]->category_budget
                    ]
                );
            }
        }

        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('plans')
                ->join('participants', 'participants.id', '=', 'plans.participant_id')
                ->where('plans.participant_id', '=', $participant_id)
                ->whereNull('plans.deleted_at')
                ->selectRaw(
                    'plans.*
           '
                )->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.participantsplan');
    }

    public function editrecordplan($id)
    {
        $plan = DB::connection($this->connection)->table('plans')->where('id', '=', $id)->whereNull('plans.deleted_at')->get()->first();

        return response()->json($plan);
    }

    public function deleterecordplan(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            if (empty($id)) {
                return response()->json(['success' => false, 'msg' => 'Please select item']);
            }

            // DB::connection($this->connection)->table('plan_documents')->where('plan_id', $id)->delete();

            DB::connection($this->connection)->table('plan_documents')
                ->where('plan_id', $id)
                ->update(['deleted_at' => now()]);

            // DB::connection($this->connection)->table('plans')->where('id','=',$id)->delete();

            DB::connection($this->connection)->table('plans')
                ->where('id', $id)
                ->update(['deleted_at' => now()]);

            return response()->json(['success' => 'Record deleted successfully.']);
        }
    }

    public function loadrecordcapacitybuilding(Request $request)
    {
        if ($request->ajax()) {

            $this->PlanDetail->connection = $this->connection;

            $planDetails = DB::connection($this->connection)
                ->table('plan_details')
                ->selectRaw('id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id')
                ->where('plan_details.plan_id', '=', $request->plan_id)->get();

                $capacityInvoices = DB::connection($this->connection)
                ->table('invoice_details')
                ->selectRaw('invoices.id, invoice_details.id as invoicedetail_id, invoices.participant_id, invoice_details.ndis_pricingguide_id, invoice_details.amount as amount, invoices.serviceprovider_id, plan_supportreference.support_categories_id')
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                ->where('invoices.participant_id', '=', $request->participant_id)
                ->where('plan_supportreference.support_purposes_id', '=', 3)
                ->whereNull('invoice_details.deleted_at')
                ->whereNull('invoices.deleted_at')
                ->distinct()
                ->get();


            foreach ($planDetails as $key => $plandetail) {

                $detail = DB::connection($this->connection)
                    ->table('plan_details')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                    ->where('plan_details.id', $plandetail->id)
                    ->whereNull('plan_details.deleted_at')
                    ->where('plan_supportreference.support_purposes_id', '=', 3)
                    ->update(['plan_details.remaining_budget' =>  $plandetail->category_budget]);
            }

            foreach ($capacityInvoices as $key => $capacityInvoice) {

                $deducted = false;

                $planDetailWithQFs = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id, plandetails_stateditems.ndis_pricingguides_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                    ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                    ->where('plan_supportreference.support_purposes_id', '=', 3)
                    ->where('plan_details.has_quarantine_fund', '=', 1)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();


                $planDetailWithStatedItems = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, plandetails_stateditems.ndis_pricingguides_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                    ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                    ->where('plan_supportreference.support_purposes_id', '=', 3)
                    ->where('plan_details.has_quarantine_fund', '=', 0)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();



                $planDetailWithNoQFs = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                    ->where('plan_supportreference.support_purposes_id', '=', 3)
                    ->where('plan_details.has_quarantine_fund', '=', 0)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();

                foreach ($planDetailWithQFs as $key => $planDetailWithQF) {
                    if (
                        $planDetailWithQF->ndis_pricingguides_id == $capacityInvoice->ndis_pricingguide_id &&
                        $planDetailWithQF->participant_serviceproviders_id == $capacityInvoice->serviceprovider_id
                    ) {

                        DB::connection($this->connection)
                            ->table('plan_details')
                            ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                            ->where('plan_details.id', $planDetailWithQF->id)
                            ->where('plan_supportreference.support_purposes_id', '=', 3)
                            ->where('plan_details.participant_serviceproviders_id', $capacityInvoice->serviceprovider_id)
                            ->whereNull('plan_details.deleted_at')
                            ->update(['plan_details.remaining_budget' =>  $planDetailWithQF->remaining_budget - $capacityInvoice->amount]);
                        $deducted = true;
                        break;
                    }
                }

                if (!$deducted) {

                    foreach ($planDetailWithStatedItems as $key => $planDetailWithStatedItem) {
                        if (
                            $planDetailWithStatedItem->ndis_pricingguides_id == $capacityInvoice->ndis_pricingguide_id
                        ) {

                            DB::connection($this->connection)
                                ->table('plan_details')
                                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                                ->where('plan_details.id', $planDetailWithStatedItem->id)
                                ->whereNull('plan_details.deleted_at')
                                ->where('plan_supportreference.support_purposes_id', '=', 3)
                                ->update(['plan_details.remaining_budget' =>  $planDetailWithStatedItem->remaining_budget - $capacityInvoice->amount]);

                            $deducted = true;
                            break;
                        }
                    }


                    if (!$deducted) {
                        foreach ($planDetailWithNoQFs as $key => $planDetailWithNoQF) {

                            if ($planDetailWithNoQF->support_categories_id == $capacityInvoice->support_categories_id) {

                                DB::connection($this->connection)
                                    ->table('plan_details')
                                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                                    ->where('plan_details.id', $planDetailWithNoQF->id)
                                    ->whereNull('plan_details.deleted_at')
                                    ->where('plan_supportreference.support_purposes_id', '=', 3)
                                    ->update(['plan_details.remaining_budget' =>  $planDetailWithNoQF->remaining_budget - $capacityInvoice->amount]);
                                $deducted = true;
                                break;
                            }
                        }
                    }

                }

                // $detail = DB::connection($this->connection)
                //     ->table('plan_details')
                //     ->where('plan_details.id', $plandetail->id)
                //     ->whereNull('plan_details.deleted_at')
                //     ->update(['plan_details.remaining_budget' => $capacityInvoice->amount]);
            }


            $data = DB::connection($this->connection)->table('plan_details')
                ->select(
                    DB::raw(
                        "
                   plan_details.id,
                   outcome_domains.outcome_domain,
                   support_categories.support_category,
                   (CASE
                       WHEN plan_details.has_stated_item = '1' THEN 'YES'
                       ELSE 'NO'
                       END)
                       AS has_stated_items,
                   plan_details.category_budget,
                   plan_details.remaining_budget,
                   plan_details.has_stated_item,
                   (CASE
                            WHEN plan_details.has_quarantine_fund = '1' THEN 'YES'
                            ELSE 'NO'
                            END)
                   AS has_quarantine_funds,
                   plan_details.details,
                   plan_details.support_payment,
                   ndis_pricingguides.support_item_number,
                   ndis_pricingguides.support_item_name,
                   service_providers.firstname as serviceprovider_firstname,
                    service_providers.lastname as serviceprovider_lastname"
                    )
                )
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
                ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
                ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
                ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->where('plans.participant_id', '=', $request->participant_id)
                ->where('plan_details.plan_id', '=', $request->plan_id)
                ->where('support_purposes.id', '=', 3)
                ->whereNull('plan_details.deleted_at')
                ->whereNull('plans.deleted_at')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.participantsplan');
    }

    public function loadrecordcapital(Request $request)
    {
        if ($request->ajax()) {

            $this->PlanDetail->connection = $this->connection;

            $planDetails = DB::connection($this->connection)
                ->table('plan_details')
                ->selectRaw('id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id')
                ->where('plan_details.plan_id', '=', $request->plan_id)->get();


            $capitalInvoices = DB::connection($this->connection)
                ->table('invoice_details')
                ->selectRaw('invoices.id, invoice_details.id as invoicedetails_id, invoices.participant_id, invoice_details.ndis_pricingguide_id, invoice_details.amount as amount, invoices.serviceprovider_id, plan_supportreference.support_categories_id')
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                ->where('invoices.participant_id', '=', $request->participant_id)
                ->where('plan_supportreference.support_purposes_id', '=', 2)
                ->whereNull('invoice_details.deleted_at')
                ->whereNull('invoices.deleted_at')
                ->distinct()
                ->get();


            foreach ($planDetails as $key => $plandetail) {

                $detail = DB::connection($this->connection)
                    ->table('plan_details')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                    ->where('plan_details.id', $plandetail->id)
                    ->whereNull('plan_details.deleted_at')
                    ->where('plan_supportreference.support_purposes_id', '=', 2)
                    ->update(['plan_details.remaining_budget' =>  $plandetail->category_budget]);
            }


            foreach ($capitalInvoices as $key => $capitalInvoice) {

                $deducted = false;
                $planDetailWithQFs = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id, plandetails_stateditems.ndis_pricingguides_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                    ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                    ->where('plan_supportreference.support_purposes_id', '=', 2)
                    ->where('plan_details.has_quarantine_fund', '=', 1)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();


                $planDetailWithStatedItems = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, plandetails_stateditems.ndis_pricingguides_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                    ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                    ->where('plan_supportreference.support_purposes_id', '=', 2)
                    ->where('plan_details.has_quarantine_fund', '=', 0)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();


                $planDetailWithNoQFs = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                    ->where('plan_supportreference.support_purposes_id', '=', 2)
                    ->where('plan_details.has_quarantine_fund', '=', 0)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();

                foreach ($planDetailWithQFs as $key => $planDetailWithQF) {

                    if (
                        $planDetailWithQF->ndis_pricingguides_id == $capitalInvoice->ndis_pricingguide_id &&
                        $planDetailWithQF->participant_serviceproviders_id == $capitalInvoice->serviceprovider_id
                    ) {

                        DB::connection($this->connection)
                            ->table('plan_details')
                            ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                            ->where('plan_details.id', $planDetailWithQF->id)
                            ->where('plan_details.participant_serviceproviders_id', $capitalInvoice->serviceprovider_id)
                            ->whereNull('plan_details.deleted_at')
                            ->where('plan_supportreference.support_purposes_id', '=', 2)
                            ->update(['plan_details.remaining_budget' =>  $planDetailWithQF->remaining_budget - $capitalInvoice->amount]);
                        $deducted = true;
                        break;
                    }
                }

                if (!$deducted) {

                    foreach ($planDetailWithStatedItems as $key => $planDetailWithStatedItem) {
                        if (
                            $planDetailWithStatedItem->ndis_pricingguides_id == $capitalInvoice->ndis_pricingguide_id
                        ) {

                            DB::connection($this->connection)
                                ->table('plan_details')
                                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                                ->where('plan_details.id', $planDetailWithStatedItem->id)
                                ->whereNull('plan_details.deleted_at')
                                ->where('plan_supportreference.support_purposes_id', '=', 2)
                                ->update(['plan_details.remaining_budget' =>  $planDetailWithStatedItem->remaining_budget - $capitalInvoice->amount]);

                            $deducted = true;
                            break;
                        }
                    }

                    if (!$deducted) {
                        foreach ($planDetailWithNoQFs as $key => $planDetailWithNoQF) {

                            if ($planDetailWithNoQF->support_categories_id == $capitalInvoice->support_categories_id) {

                                DB::connection($this->connection)
                                    ->table('plan_details')
                                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                                    ->where('plan_details.id', $planDetailWithNoQF->id)
                                    ->whereNull('plan_details.deleted_at')
                                    ->where('plan_supportreference.support_purposes_id', '=', 2)
                                    ->update(['plan_details.remaining_budget' =>  $planDetailWithNoQF->remaining_budget - $capitalInvoice->amount]);

                                $deducted = true;
                                break;
                            }
                        }
                    }

                }
            }


            $data = DB::connection($this->connection)->table('plan_details')
                ->select(
                    DB::raw(
                        "
               plan_details.id,
               outcome_domains.outcome_domain,
               support_categories.support_category,
               (CASE
                   WHEN plan_details.has_stated_item = '1' THEN 'YES'
                   ELSE 'NO'
                   END)
                   AS has_stated_items,
               plan_details.category_budget,
               plan_details.remaining_budget,
               plan_details.has_stated_item,
               (CASE
                        WHEN plan_details.has_quarantine_fund = '1' THEN 'YES'
                        ELSE 'NO'
                        END)
               AS has_quarantine_funds,
               plan_details.details,
               plan_details.support_payment,
               ndis_pricingguides.support_item_number,
               ndis_pricingguides.support_item_name,
               service_providers.firstname as serviceprovider_firstname,
               service_providers.lastname as serviceprovider_lastname"
                    )
                )
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
                ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
                ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
                ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->where('plans.participant_id', '=', $request->participant_id)
                ->where('plan_details.plan_id', '=', $request->plan_id)
                ->where('support_purposes.id', '=', 2)
                ->whereNull('plan_details.deleted_at')
                ->whereNull('plans.deleted_at')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.participantsplan');
    }

    public function loadrecordcoresupports(Request $request)
    {

        if ($request->ajax()) {

            $this->PlanDetail->connection = $this->connection;

            $planDetails = DB::connection($this->connection)
                ->table('plan_details')
                ->selectRaw('id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id')
                ->where('plan_details.plan_id', '=', $request->plan_id)->get();

                $coreInvoices = DB::connection($this->connection)
                ->table('invoice_details')
                ->selectRaw('invoices.id, invoice_details.id as invoicedetail_id, invoices.participant_id, invoice_details.ndis_pricingguide_id, invoice_details.amount as amount, invoices.serviceprovider_id, plan_supportreference.support_categories_id')
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                ->where('invoices.participant_id', '=', $request->participant_id)
                ->where('plan_supportreference.support_purposes_id', '=', 1)
                ->whereNull('invoice_details.deleted_at')
                ->whereNull('invoices.deleted_at')
                ->distinct()
                ->get();


            foreach ($planDetails as $key => $plandetail) {

                $detail = DB::connection($this->connection)
                    ->table('plan_details')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                    ->where('plan_details.id', $plandetail->id)
                    ->whereNull('plan_details.deleted_at')
                    ->where('plan_supportreference.support_purposes_id', '=', 1)
                    ->update(['plan_details.remaining_budget' =>  $plandetail->category_budget]);
            }

            foreach ($coreInvoices as $key => $coreInvoice) {

                $deducted = false;

                $planDetailWithQFs = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id, plandetails_stateditems.ndis_pricingguides_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                    ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                    ->where('plan_supportreference.support_purposes_id', '=', 1)
                    ->where('plan_details.has_quarantine_fund', '=', 1)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();


                $planDetailWithStatedItems = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, plandetails_stateditems.ndis_pricingguides_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                    ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                    ->where('plan_supportreference.support_purposes_id', '=', 1)
                    ->where('plan_details.has_quarantine_fund', '=', 0)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();



                $planDetailWithNoQFs = DB::connection($this->connection)
                    ->table('plan_details')
                    ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id, plan_supportreference.support_categories_id')
                    ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                    ->where('plan_supportreference.support_purposes_id', '=', 1)
                    ->where('plan_details.has_quarantine_fund', '=', 0)
                    ->whereNull('plan_details.deleted_at')
                    ->whereNull('plans.deleted_at')
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->distinct()
                    ->get();

                foreach ($planDetailWithQFs as $key => $planDetailWithQF) {
                    if (
                        $planDetailWithQF->ndis_pricingguides_id == $coreInvoice->ndis_pricingguide_id &&
                        $planDetailWithQF->participant_serviceproviders_id == $coreInvoice->serviceprovider_id
                    ) {

                        DB::connection($this->connection)
                            ->table('plan_details')
                            ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                            ->where('plan_details.id', $planDetailWithQF->id)
                            ->where('plan_supportreference.support_purposes_id', '=', 1)
                            ->where('plan_details.participant_serviceproviders_id', $coreInvoice->serviceprovider_id)
                            ->whereNull('plan_details.deleted_at')
                            ->update(['plan_details.remaining_budget' =>  $planDetailWithQF->remaining_budget - $coreInvoice->amount]);
                        $deducted = true;
                        break;
                    }
                }

                if (!$deducted) {

                    foreach ($planDetailWithStatedItems as $key => $planDetailWithStatedItem) {
                        if (
                            $planDetailWithStatedItem->ndis_pricingguides_id == $coreInvoice->ndis_pricingguide_id
                        ) {

                            DB::connection($this->connection)
                                ->table('plan_details')
                                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                                ->where('plan_details.id', $planDetailWithStatedItem->id)
                                ->whereNull('plan_details.deleted_at')
                                ->where('plan_supportreference.support_purposes_id', '=', 1)
                                ->update(['plan_details.remaining_budget' =>  $planDetailWithStatedItem->remaining_budget - $coreInvoice->amount]);

                            $deducted = true;
                            break;
                        }
                    }


                    if (!$deducted) {
                        foreach ($planDetailWithNoQFs as $key => $planDetailWithNoQF) {

                            if ($planDetailWithNoQF->support_categories_id == $coreInvoice->support_categories_id) {

                                DB::connection($this->connection)
                                    ->table('plan_details')
                                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                                    ->where('plan_details.id', $planDetailWithNoQF->id)
                                    ->whereNull('plan_details.deleted_at')
                                    ->where('plan_supportreference.support_purposes_id', '=', 1)
                                    ->update(['plan_details.remaining_budget' =>  $planDetailWithNoQF->remaining_budget - $coreInvoice->amount]);
                                $deducted = true;
                                break;
                            }
                        }
                    }

                }

                // $detail = DB::connection($this->connection)
                //     ->table('plan_details')
                //     ->where('plan_details.id', $plandetail->id)
                //     ->whereNull('plan_details.deleted_at')
                //     ->update(['plan_details.remaining_budget' => $coreInvoice->amount]);
            }


            $data = DB::connection($this->connection)->table('plan_details')
                ->select(
                    DB::raw(
                        "
                   plan_details.id,
                   outcome_domains.outcome_domain,
                   support_categories.support_category,
                   (CASE
                       WHEN plan_details.has_stated_item = '1' THEN 'YES'
                       ELSE 'NO'
                       END)
                       AS has_stated_items,
                   plan_details.category_budget,
                   plan_details.remaining_budget,
                   plan_details.has_stated_item,
                   (CASE
                            WHEN plan_details.has_quarantine_fund = '1' THEN 'YES'
                            ELSE 'NO'
                            END)
                   AS has_quarantine_funds,
                   plan_details.details,
                   plan_details.support_payment,
                   ndis_pricingguides.support_item_number,
                   ndis_pricingguides.support_item_name,
                   service_providers.firstname as serviceprovider_firstname,
                    service_providers.lastname as serviceprovider_lastname"
                    )
                )
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
                ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
                ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
                ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->where('plans.participant_id', '=', $request->participant_id)
                ->where('plan_details.plan_id', '=', $request->plan_id)
                ->where('support_purposes.id', '=', 1)
                ->whereNull('plan_details.deleted_at')
                ->whereNull('plans.deleted_at')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.participantsplan');
    }

    public function saverecordplandetails(Request $request)
    {

        if ($request->ajax()) {

            // $stated_items_id = json_decode(stripslashes($request->input('stated_items_id', true)));
            // $stated_items_budget = json_decode(stripslashes($request->input('stated_items_budget', true)));


            $plan = DB::connection($this->connection)->table('plans')->where('id', '=', $request->plan_id)->get()->first();

            $total_funding = $plan->total_funding;

            $data = DB::connection($this->connection)->table('plan_supportreference')->where([
                ['outcome_domains_id', '=', $request->input('outcome_domains_id')],
                ['support_categories_id', '=', $request->input('support_categories_id')]
            ])->get();

            //avoid duplication
            if ($request->has_stated_item === 0) {
                $duplicateFound = DB::connection($this->connection)->table('plan_details')
                    ->select(DB::raw("plan_details.id, plan_details.plan_supportreference_id, plan_details.participant_serviceproviders_id, plan_details.has_quarantine_fund"))
                    ->where('plan_details.plan_supportreference_id', '=', $data[0]->id) // support category
                    ->where('plan_details.has_quarantine_fund', '=', 0) //qf
                    ->where('has_stated_item', '=', 0)
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->where('plan_details.id', '!=', $request->planDetails_id)
                    ->whereNull('plan_details.deleted_at')
                    ->get()->first();

                if (!empty($duplicateFound)) {

                    return response()->json(['has_error' => true, 'msg' => 'Cannot save duplicate record.']);

                }
            } else {

                $duplicateFound = DB::connection($this->connection)->table('plan_details')
                    ->select(DB::raw("plan_details.id, plan_details.plan_supportreference_id, plan_details.participant_serviceproviders_id, plan_details.has_quarantine_fund"))
                    ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                    ->where('plan_details.plan_supportreference_id', '=', $data[0]->id) // support category
                    ->where('plan_details.participant_serviceproviders_id', '=', ($request->participant_serviceproviders_id) ? ($request->participant_serviceproviders_id) : null) // sp
                    ->where('plan_details.has_quarantine_fund', '=', (bool)$request->has_quarantine_funds) //qf
                    ->where('plan_details.has_stated_item', '=', (bool)$request->has_stated_item) //si
                    ->where('plan_details.plan_id', '=', $request->plan_id)
                    ->where('plandetails_stateditems.ndis_pricingguides_id', '=', $request->stated_items_id)
                    ->where('plan_details.id', '!=', $request->planDetails_id)
                    ->whereNull('plan_details.deleted_at')
                    ->get()->first();

                if (!empty($duplicateFound)) {
                    return response()->json(['has_error' => true, 'msg' => 'Cannot save duplicate record.']);
                }
            }

            $participant_id = DB::connection($this->connection)->table('plans')->select(DB::raw("participant_id as id"))
                ->where('plans.id', '=', $plan->id)
                ->get();

            if (
                empty($request->plan_id) ||
                empty($request->support_categories_id) ||
                empty($request->outcome_domains_id)
                //    empty($request->category_budget)
            ) {

                return false;
            }

            if (!((float)$request->category_budget <= (float)$total_funding)) {

                return response()->json(['has_error' => true, 'msg' => 'overbudget']);
            }





            //$participant_serviceproviders_id = NULL;

            // if(((bool)$request->has_quarantine_funds)){
            //     $participant_serviceproviders_id = $request->participant_serviceproviders_id;
            // }

            if (((bool)$request->has_quarantine_funds) && empty($request->participant_serviceproviders_id)) {
                return response()->json(['has_error' => true, 'msg' => 'Please select service provider.']);
            }

            if (((bool)$request->has_stated_item) && empty($request->stated_items_id)) {
                return response()->json(['has_error' => true, 'msg' => 'Please select stated item.']);
            }

            $this->PlanDetail->connection = $this->connection;
            $PlanDetail = $this->PlanDetail->updateOrCreate(
                ['id' => $request->planDetails_id],
                [
                    'plan_id' => $request->plan_id,
                    'plan_supportreference_id' => $data[0]->id,
                    'category_budget' => $request->category_budget,
                    'has_stated_item' => (bool)$request->has_stated_item,
                    'has_quarantine_fund' => (bool)$request->has_quarantine_funds,
                    'participant_serviceproviders_id' => $request->participant_serviceproviders_id,
                    'details' => $request->details,
                    'support_payment' => $request->support_payment
                ]
            );
            if (((bool)$request->has_stated_item)) {

                if (empty($request->stated_items_id)) {

                    return response()->json(['has_error' => true, 'msg' => 'Please select stated item.']);
                }
                $isExist = DB::connection($this->connection)->table('plandetails_stateditems')->where([
                    ['plan_details_id', '=', $PlanDetail->id]
                ])->exists();
                if (!empty($isExist)) {
                    DB::connection($this->connection)->table('plandetails_stateditems')->where([
                        ['plan_details_id', '=', $request->planDetails_id]

                    ])->delete();
                }
                $this->PlandetailsStateditems->connection = $this->connection;
                $this->PlandetailsStateditems->updateOrCreate(
                    ['id' => 0],
                    [
                        'plan_details_id' => $PlanDetail->id,
                        'ndis_pricingguides_id' => $request->stated_items_id,
                        'stated_item_budget' => 0
                    ]
                );
            } else {
                DB::connection($this->connection)->table('plandetails_stateditems')->where([
                    ['plan_details_id', '=', $request->planDetails_id]

                ])->delete();
            }


            $totalAlloc = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->where('plan_details.plan_id', '=', $request->plan_id)
                ->whereNull('plan_details.deleted_at')
                ->get();

            $remaining_budget = (float)$plan->total_funding - (float)$totalAlloc[0]->category_budget;

            $this->Plans->connection = $this->connection;
            $this->Plans->updateOrCreate(
                ['id' => $request->plan_id],
                [
                    'total_allocated' => $totalAlloc[0]->category_budget,
                    'total_remaining' => $remaining_budget,
                ]
            );

            $core_support_total_budget = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->where([
                    ['plan_supportreference.support_purposes_id', '=', 1],
                    ['plan_details.plan_id', '=', $request->plan_id]
                ])
                ->whereNull('plan_details.deleted_at')
                ->get();


            $capital_total_budget = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->where([
                    ['plan_supportreference.support_purposes_id', '=', 2],
                    ['plan_details.plan_id', '=', $request->plan_id]
                ])
                ->whereNull('plan_details.deleted_at')
                ->get();

            $capacity_building_total_budget = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->where([
                    ['plan_supportreference.support_purposes_id', '=', 3],
                    ['plan_details.plan_id', '=', $request->plan_id]
                ])
                ->whereNull('plan_details.deleted_at')
                ->get();

            $totalInvoiceAmount = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::raw("SUM(invoice_details.amount) as amount"))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where('invoices.participant_id', '=', $participant_id[0]->id)
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->get();


            $id = $request->plan_id;
            $totalAllocated = $totalAlloc[0]->category_budget;
            $coreBudget = $core_support_total_budget[0]->category_budget;
            $capitalBudget = $capital_total_budget[0]->category_budget;
            $capacityBudget = $capacity_building_total_budget[0]->category_budget;
            $outcomeDomainId = $request->outcome_domains_id;
            $categoryId = $request->support_categories_id;

            $totalClaimed = (float) $totalInvoiceAmount[0]->amount;
            $remaining_allocated = (float)$totalAllocated - (float)$totalClaimed;


            $updatedData = DB::connection($this->connection)->table('plan_details')->select(DB::raw("
            plan_supportreference.support_purposes_id, 
            plans.total_delivered as totalSpent, 
            '$remaining_allocated' as totalRemaining,
            '$totalAllocated' as totalAllocated, 
            '$coreBudget' as coreBudget,
            '$capitalBudget' as capitalBudget,
            '$capacityBudget' as capacityBudget       
            "))
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->where([
                    ['plan_supportreference.outcome_domains_id', '=', $outcomeDomainId],
                    ['plan_supportreference.support_categories_id', '=', $categoryId],
                    ['plan_details.plan_id', '=', $id]
                ])
                ->whereNull('plan_details.deleted_at')
                ->get();

            return response()->json(['has_error' => false, 'msg' => 'Record saved successfully.', 'data' => $updatedData[0]]);
        }
    }

    public function loadrecordplandetailstateditems(Request $request)
    {

        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('plandetails_stateditems')
                ->join('stated_items', 'stated_items.id', '=', 'plandetails_stateditems.stated_items_id')
                ->where('plandetails_stateditems.plan_details_id', '=', $request->plan_id)
                ->whereNull('plandetails_stateditems.deleted_at')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteItem"><i class="fa fa-trash"><i/></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.participantsplan');
    }

    public function editrecordplandetails($id)
    {

        $data = DB::connection($this->connection)->table('plan_details')
            ->select(
                DB::raw(
                    "
           plan_details.id,
           plan_supportreference.outcome_domains_id,
           plan_supportreference.support_categories_id,
           support_categories.support_category,
           plan_details.category_budget,
           plan_details.has_stated_item,
           plandetails_stateditems.stated_items_id,
           plandetails_stateditems.ndis_pricingguides_id,
           plan_details.details,
           plan_details.support_payment,
           plan_details.has_quarantine_fund,
           plan_details.participant_serviceproviders_id,
           plan_details.participant_supportcoordinators_id

           "
                )
            )
            ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
            ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
            ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
            ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
            ->where('plan_details.id', '=', $id)
            ->whereNull('plan_details.deleted_at')
            ->get();

        return response()->json($data);
    }

    public function deleterecordsupportpurpose(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            if (empty($id)) {
                return response()->json(['success' => false, 'msg' => 'Please select item']);
            }



            $plan = DB::connection($this->connection)->table('plans')->where('id', '=', $request->plan_id)->get()->first();
            $plan_id = $request->plan_id;

            $participant_id = DB::connection($this->connection)->table('plans')->select(DB::raw("participant_id as id"))
                ->where('plans.id', '=', $plan->id)
                ->get();

            $purposes_id = DB::connection($this->connection)->table('plan_details')->select(DB::raw("plan_details.id, plan_supportreference.support_purposes_id, plan_details.category_budget"))
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->where('plan_details.id', '=', $id)
                ->whereNull('plan_details.deleted_at')
                ->get();

            $totalAlloc = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->where('plan_details.plan_id', '=', $request->plan_id)
                ->whereNull('plan_details.deleted_at')
                ->get();

            $remaining_budget = (float)$plan->total_funding - (float)$totalAlloc[0]->category_budget;

            $core_support_total_budget = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->where([
                    ['plan_supportreference.support_purposes_id', '=', 1],
                    ['plan_details.plan_id', '=', $request->plan_id]
                ])
                ->whereNull('plan_details.deleted_at')
                ->get();


            $capital_total_budget = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->where([
                    ['plan_supportreference.support_purposes_id', '=', 2],
                    ['plan_details.plan_id', '=', $request->plan_id]
                ])
                ->whereNull('plan_details.deleted_at')
                ->get();

            $capacity_building_total_budget = DB::connection($this->connection)->table('plan_details')
                ->select(DB::raw("SUM(category_budget) as category_budget"))
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->where([
                    ['plan_supportreference.support_purposes_id', '=', 3],
                    ['plan_details.plan_id', '=', $request->plan_id]
                ])
                ->whereNull('plan_details.deleted_at')
                ->get();

            $totalInvoiceAmount = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::raw("SUM(invoice_details.amount) as amount"))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where('invoices.participant_id', '=', $participant_id[0]->id)
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->get();

            $totalAllocated = $totalAlloc[0]->category_budget;
            $coreBudget = $core_support_total_budget[0]->category_budget;
            $capitalBudget = $capital_total_budget[0]->category_budget;
            $capacityBudget = $capacity_building_total_budget[0]->category_budget;
            $support_purposes_id = $purposes_id[0]->support_purposes_id;
            $totalAllocated = $totalAllocated - $purposes_id[0]->category_budget;

            $totalClaimed = (float) $totalInvoiceAmount[0]->amount;
            $remaining_allocated = (float)$totalAllocated - (float)$totalClaimed;


            switch ($support_purposes_id) {
                case 1:
                    $coreBudget = $coreBudget - $purposes_id[0]->category_budget;
                    break;
                case 2:
                    $capitalBudget = $capitalBudget - $purposes_id[0]->category_budget;
                    break;
                case 3:
                    $capacityBudget = $capacityBudget - $purposes_id[0]->category_budget;
                    break;
            }

            $updatedData = DB::connection($this->connection)->table('plan_details')->select(DB::raw("
            plans.total_delivered as totalSpent, 
            '$remaining_allocated' as totalRemaining,
            '$totalAllocated' as totalAllocated, 
            '$coreBudget' as coreBudget,
            '$capitalBudget' as capitalBudget,
            '$capacityBudget' as capacityBudget,
            '$support_purposes_id' as support_purposes_id       
            "))
                ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->where([
                    ['plan_details.plan_id', '=', $plan_id]
                ])
                ->get();

            // DB::connection($this->connection)->table('plan_details')->where('id','=',$id)->delete();

            DB::connection($this->connection)->table('plan_details')
                ->where('id', $id)
                ->update(['deleted_at' => now()]);

            // DB::connection($this->connection)->table('plandetails_stateditems')->where([
            //     ['plan_details_id', '=', $id]
            // ])->delete();

            DB::connection($this->connection)->table('plandetails_stateditems')
                ->where('plan_details_id', $id)
                ->update(['deleted_at' => now()]);

            return response()->json(['success' => true, 'msg' => 'Record deleted successfully.', 'data' => $updatedData[0]]);
            //return response()->json(['has_error' => false, 'msg' => 'Record deleted successfully.', 'data' => $updatedData[0]]);
        }
    }

    public function uploadPlanDocument(Request $request)
    {
        $resp = array();
        $host = $request->getHttpHost();

        $file = $request->plan_document;
        $fileName = $request->plan_document->getClientOriginalName();
        $originalName = pathinfo($fileName, PATHINFO_FILENAME);
        $fileExt = $request->plan_document->extension();
        $uniqueS3DocName =  Str::uuid() . '.' . $fileExt;
        if ($host == env('PLANAJI_HOST')) {
            $filePath =  'planaji/plan-document/';
        } elseif ($host == env('PLANONTRACK_HOST')) {
            $filePath =  'plan_on_track/plan-document/';
        } else {
            $filePath = 'temp/plan-document/';
        }
        $fileKey = $filePath . $uniqueS3DocName;

        $this->PlanDocument->connection = $this->connection;

        try {
            $path = Storage::disk('s3AllPrivateMedia')->put($fileKey, file_get_contents($file));
            $planDocument = $this->PlanDocument->create([
                'plan_id' => $request->plan_id,
                'file_name' => $originalName,
                'file_type' => $fileExt,
                's3_filepath' => $filePath,
                's3_key' => $uniqueS3DocName
            ]);
            $resp['status'] = true;
            $resp['data'] = $planDocument;
        } catch (\Exception $e) {
            $resp['status'] = false;
            $resp['error_msg'] = $e->getMessage();
        }
        return response()->json($resp);
    }

    public function getPlanDocument(Request $request)
    {
        $client = Storage::disk('s3AllPrivateMedia')->getDriver()->getAdapter()->getClient();
        $bucket = config('filesystems.disks.s3AllPrivateMedia.bucket');
        $key = $request->path . "" . $request->key;
        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $key
        ]);
        $request = $client->createPresignedRequest($command, '+20 minutes');
        $presignedUrl = (string)$request->getUri();
        return response()->json(['document_url' => $presignedUrl]);
    }

    public function deletePlanDocument($id)
    {
        $resp = array();
        $planDocument = DB::connection($this->connection)->table('plan_documents')->where('id', $id)->first();

        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

        $key = $planDocument->s3_key;
        try {
            DB::connection($this->connection)->table('plan_documents')->where('id', $id)->delete();
            Storage::disk('s3AllPrivateMedia')->delete($planDocument->s3_filepath . '/' . $key);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        $resp['status'] = true;
        return response()->json($resp);
    }

    public function getLatestPlanDocument(Request $request)
    {
        $resp = array();
        $id = $request->id;
        $data = DB::connection($this->connection)->table('plan_documents')->where('id', $id)->whereNull('deleted_at')->first();
        if ($data) {
            $resp['status'] = true;
            $resp['data'] = $data;
        } else {
            $resp['status'] = false;
            $resp['error_msg'] = 'Something went wrong!';
        }
        return response()->json($resp);
    }
}
