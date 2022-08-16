<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Invoices;
use App\User;
use App\InvoiceDetails;
use App\InvoiceLinkemails;
use App\InvoiceEmail;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use App\InvoiceDocument;
use App\Mail\AlertZeroBudget;
use App\PlanDetail;
use App\PlandetailsStateditems;
use App\Participants;
use App\Plans;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendNotifLowBudget;
use Illuminate\Support\Facades\Config;
use PDO;

class InvoicesController extends Controller
{

    private $connection = NULL;
    private $Invoices = NULL;
    private $InvoiceDetails = NULL;
    private $InvoiceEmail = NULL;

    private $Participants = NULL;
    private $Plans = NULL;
    private $PlanDetail = NULL;


    public function __construct()
    {
        $this->Invoices = new Invoices;
        $this->InvoiceDetails = new InvoiceDetails;
        $this->InvoiceLinkemails = new InvoiceLinkemails;
        $this->InvoiceEmail = new InvoiceEmail;
        $this->User = new User;

        $this->Invoices->connection = $this->connection;
        $this->InvoiceDetails->connection = $this->connection;
        $this->InvoiceLinkemails->connection = $this->connection;
        $this->InvoiceEmail->connection = $this->connection;

        $this->Participants = new Participants;
        $this->Plans = new Plans;
        $this->PlanDetail = new PlanDetail;

        $this->middleware(function ($request, $next) {
            $this->connection = auth()->user()['connection'];
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
        return view('admin.invoices');
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
    public function add()
    {

        $service_providers = DB::connection($this->connection)->table('service_providers')
            ->where('service_providers.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('service_providers.deleted_at')
            ->get();

        $participants = DB::connection($this->connection)->table('participants')
            ->where('participants.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('participants.deleted_at')
            ->get();

        $invoice_emails = DB::connection($this->connection)->table('invoice_emails')
            ->where('invoice_emails.plan_manager_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('invoice_emails.deleted_at')
            ->get();

        $gst = DB::connection($this->connection)->table('gst')->get();

        $claim_type = DB::connection($this->connection)->table('claim_type')->get();

        $route = 'Add';

        $user_auth = $this->connection;




        return view('admin.invoicedetails', compact('user_auth', 'route', 'participants', 'service_providers', 'invoice_emails', 'gst', 'claim_type'));
    }

    public function duplicate($id)
    {

        $invoices = DB::connection($this->connection)->table('invoices')
            ->selectRaw(
                "
            invoices.id,
            participants.ndis_number,
            invoices.invoice_number,
            invoices.invoice_date,
            invoices.due_date,
            invoices.reference_number,
            service_providers.abn as abn,
            invoices.service_provider_acc_number,
            invoices.status,
            service_providers.firstname as service_provider_first_name,
            service_providers.lastname as service_provider_last_name,
            invoices.serviceprovider_id,
            invoices.status,
            invoices.remarks,
            participants.id as participant_id
            "
            )
            ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
            ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
            ->where('invoices.id', '=', $id)
            ->whereNull('invoices.deleted_at')
            ->orderBy('invoices.id', 'desc')
            ->get();

        $invoice_details = DB::connection($this->connection)->table('invoice_details')
            ->selectRaw(
                "
            invoice_details.*,
            ndis_pricingguides.*,
            gst.code as gstcode,
            gst.description as gstdesc,
            claim_type.code as claimtypecode,
            claim_type.description as claimtypedesc,
            cancellation_reason.code as cancelcode,
            cancellation_reason.description as canceldesc,
            invoice_details.id
            "
            )
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
            ->leftJoin('claim_type', 'claim_type.id', '=', 'invoice_details.claim_type_id')
            ->leftJoin('cancellation_reason', 'cancellation_reason.id', '=', 'invoice_details.cancellation_reason_id')
            ->where('invoice_details.invoice_id', '=', $id)
            ->whereNull('invoice_details.deleted_at')
            ->orderBy('invoice_details.id', 'desc')
            ->get();

        $invoice_linkEmails = DB::connection($this->connection)->table('invoice_linkemails')
            ->selectRaw(
                "invoice_linkemails.id as id,
            invoice_linkemails.*,
            invoice_emails.subject,
            invoice_emails.from_email
            "
            )
            ->leftJoin('invoice_emails', 'invoice_emails.id', '=', 'invoice_linkemails.invoice_email_id')
            ->where('invoice_linkemails.invoice_id', '=', $id)
            ->whereNull('invoice_linkemails.deleted_at')
            ->first();

        // var_dump($invoice_linkEmails);
        $response = array('invoices' => $invoices[0], 'invoice_details' => $invoice_details, 'invoice_linkemails' => $invoice_linkEmails);

        $service_providers = DB::connection($this->connection)->table('service_providers')
            ->where('service_providers.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('service_providers.deleted_at')
            ->get();

        $participants = DB::connection($this->connection)->table('participants')
            ->where('participants.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('participants.deleted_at')
            ->get();

        $invoice_emails = DB::connection($this->connection)->table('invoice_emails')
            ->where('invoice_emails.plan_manager_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('invoice_emails.deleted_at')
            ->get();

        $gst = DB::connection($this->connection)->table('gst')->get();

        $claim_type = DB::connection($this->connection)->table('claim_type')->get();

        // $data = DB::connection($this->connection)->table('ndis_pricingguides')->get();

        // return response()->json($response);

        $route = 'Duplicate';

        $user_auth = $this->connection;

        return view('admin.invoicedetails', compact('user_auth', 'route', 'response', 'participants', 'service_providers', 'invoice_emails', 'gst', 'claim_type'));
    }
    public function edit($id)
    {
        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

        $invoices = DB::connection($this->connection)->table('invoices')
            ->selectRaw(
                "
            invoices.id,
            participants.ndis_number,
            invoices.invoice_number,
            invoices.invoice_date,
            invoices.due_date,
            invoices.reference_number,
            service_providers.abn as abn,
            invoices.service_provider_acc_number,
            invoices.status,
            service_providers.firstname as service_provider_first_name,
            service_providers.lastname as service_provider_last_name,
            invoices.serviceprovider_id,
            invoices.status,
            invoices.remarks,
            participants.id as participant_id
            "
            )
            ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
            ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
            ->where('invoices.id', '=', $id)
            ->whereNull('invoices.deleted_at')
            ->where('invoices.planmanager_subscriptions_id', '=', $plan_manager_subscription_id)
            ->orderBy('invoices.id', 'desc')
            ->get();


        if (empty(count($invoices))) { // if not found then redirect to starting page of the return value.
            return redirect('invoices');
        }

        $invoice_details = DB::connection($this->connection)->table('invoice_details')
            ->selectRaw(
                "
            invoice_details.*,
            ndis_pricingguides.*,
            gst.code as gstcode,
            gst.description as gstdesc,
            claim_type.code as claimtypecode,
            claim_type.description as claimtypedesc,
            cancellation_reason.code as cancelcode,
            cancellation_reason.description as canceldesc,
            invoice_details.id,
            invoice_details.claim_reference
            "
            )
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
            ->leftJoin('claim_type', 'claim_type.id', '=', 'invoice_details.claim_type_id')
            ->leftJoin('cancellation_reason', 'cancellation_reason.id', '=', 'invoice_details.cancellation_reason_id')
            ->where('invoice_details.invoice_id', '=', $id)
            ->whereNull('invoice_details.deleted_at')
            ->orderBy('invoice_details.id', 'desc')
            ->get();

        // $supportItems = DB::connection($this->connection)->table('ndis_pricingguides')->get();

        $invoice_linkEmails = DB::connection($this->connection)->table('invoice_linkemails')
            ->selectRaw(
                "invoice_linkemails.id as id,
            invoice_linkemails.*,
            invoice_emails.subject,
            invoice_emails.from_email
            "
            )
            ->leftJoin('invoice_emails', 'invoice_emails.id', '=', 'invoice_linkemails.invoice_email_id')
            ->where('invoice_linkemails.invoice_id', '=', $id)
            ->whereNull('invoice_linkemails.deleted_at')
            ->first();

        // var_dump($invoice_linkEmails);
        $response = array('invoices' => $invoices[0], 'invoice_details' => $invoice_details, 'invoice_linkemails' => $invoice_linkEmails);

        $service_providers = DB::connection($this->connection)->table('service_providers')
            ->where('service_providers.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('service_providers.deleted_at')
            ->get();

        $participants = DB::connection($this->connection)->table('participants')
            ->where('participants.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('participants.deleted_at')
            ->get();

        $invoice_emails = DB::connection($this->connection)->table('invoice_emails')
            ->where('invoice_emails.plan_manager_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('invoice_emails.deleted_at')
            ->get();

        $invoice_status =  DB::connection($this->connection)->table('invoice_status')
            // ->where('invoice_status.id', '>', 1)
            // ->where('invoice_status.id', '!=', 3)
            ->get();

        // $data = DB::connection($this->connection)->table('ndis_pricingguides')->get();

        // return response()->json($response);

        $gst = DB::connection($this->connection)->table('gst')->get();

        $claim_type = DB::connection($this->connection)->table('claim_type')->get();
        $invoice_document = DB::connection($this->connection)->table('invoice_documents')->where('invoice_id', $id)->first();
        $invoice_url = "";
        if (!empty($invoice_document)) {
            $invoice_url = "https://" . config('filesystems.disks.s3AllPublicMedia.bucket') . ".s3." . env('AWS_DEFAULT_REGION') . ".amazonaws.com/" . $invoice_document->s3_filepath . "" . $invoice_document->s3_key;
        }
        $route = 'Edit';

        // var_dump($invoice_emails);

        $user_auth = $this->connection;

        return view('admin.invoicedetails', compact('user_auth', 'route', 'response', 'participants', 'service_providers', 'invoice_emails', 'gst', 'claim_type', 'invoice_status', 'invoice_document', 'invoice_url'));
    }

    public function view($id)
    {
        $plan_manager_subscription_id = auth()->user()->plan_manager_subscription_id;

        $invoices = DB::connection($this->connection)->table('invoices')
            ->selectRaw(
                "
            invoices.id,
            participants.ndis_number,
            invoices.invoice_number,
            invoices.invoice_date,
            invoices.due_date,
            invoices.reference_number,
            service_providers.abn,
            invoices.service_provider_acc_number,
            invoices.status,
            service_providers.firstname as service_provider_first_name,
            service_providers.lastname as service_provider_last_name,
            invoices.serviceprovider_id,
            invoices.status,
            invoices.remarks,
            participants.id as participant_id
            "
            )
            ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
            ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
            ->where('invoices.id', '=', $id)
            ->whereNull('invoices.deleted_at')
            ->where('invoices.planmanager_subscriptions_id', '=', $plan_manager_subscription_id)
            ->get();

        if (empty(count($invoices))) { // if not found then redirect to starting page of the return value.
            return redirect('invoices');
        }

        $invoice_details = DB::connection($this->connection)->table('invoice_details')
            ->selectRaw(
                "
            invoice_details.*,
            ndis_pricingguides.*,
            gst.code as gstcode,
            gst.description as gstdesc,
            claim_type.code as claimtypecode,
            claim_type.description as claimtypedesc,
            cancellation_reason.code as cancelcode,
            cancellation_reason.description as canceldesc,
            invoice_details.id,
            invoice_details.claim_reference
            "
            )
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
            ->leftJoin('claim_type', 'claim_type.id', '=', 'invoice_details.claim_type_id')
            ->leftJoin('cancellation_reason', 'cancellation_reason.id', '=', 'invoice_details.cancellation_reason_id')
            ->where('invoice_details.invoice_id', '=', $id)
            ->whereNull('invoice_details.deleted_at')
            ->get();

        $invoice_linkEmails = DB::connection($this->connection)->table('invoice_linkemails')
            ->selectRaw(
                "invoice_linkemails.id as id,
            invoice_linkemails.*,
            invoice_emails.subject,
            invoice_emails.from_email
            "
            )
            ->leftJoin('invoice_emails', 'invoice_emails.id', '=', 'invoice_linkemails.invoice_email_id')
            ->whereNull('invoice_linkemails.deleted_at')
            ->where('invoice_linkemails.invoice_id', '=', $id)
            ->first();

        // var_dump($invoice_linkEmails);
        $response = array('invoices' => $invoices[0], 'invoice_details' => $invoice_details, 'invoice_linkemails' => $invoice_linkEmails);

        $service_providers = DB::connection($this->connection)->table('service_providers')
            ->where('service_providers.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('service_providers.deleted_at')
            ->get();

        $participants = DB::connection($this->connection)->table('participants')
            ->where('participants.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('participants.deleted_at')
            ->get();

        $invoice_emails = DB::connection($this->connection)->table('invoice_emails')
            ->where('invoice_emails.plan_manager_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('invoice_emails.deleted_at')
            ->get();


        $invoice_status =  DB::connection($this->connection)->table('invoice_status')
            // ->where('invoice_status.id', '>', 1)
            // ->where('invoice_status.id', '!=', 3)
            ->get();



        // $data = DB::connection($this->connection)->table('ndis_pricingguides')->get();

        // return response()->json($response);

        $route = 'View';

        $user_auth = $this->connection = auth()->user();

        return view('admin.invoicedetails', compact('user_auth', 'route', 'response', 'participants', 'service_providers', 'invoice_emails', 'invoice_status'));
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

    public function getclaimtype(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection($this->connection)->table('claim_type')->get();
            return response()->json($data);
        }
    }

    public function getcancellationreason(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection($this->connection)->table('cancellation_reason')->get();
            return response()->json($data);
        }
    }


    public function getproviderABN(Request $request)
    {
        if ($request->ajax()) {
            $data = $data = DB::connection($this->connection)->table('service_providers')
                ->select(DB::raw("service_providers.abn"))
                ->where('service_providers.id', '=', $request->serviceprovider_id)
                ->whereNull('service_providers.deleted_at')
                ->where('planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)->get();
            return response()->json($data[0]);
        }
    }

    public function getstateditems(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection($this->connection)->table('ndis_pricingguides')->get();
            return response()->json($data);
        }
    }

    public function getgstcode(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection($this->connection)->table('gst')->get();
            return response()->json($data);
        }
    }

    public function getparticipants(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection($this->connection)->table('participants')
                ->whereNull('participants.deleted_at')
                ->where('planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)->get();
            return response()->json($data);
        }
    }

    public function getserviceprovider(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection($this->connection)->table('service_providers')
                ->whereNull('service_providers.deleted_at')
                ->where('planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)->get();
            return response()->json($data);
        }
    }

    public function exporttoproda(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::raw("
            (CASE
                   WHEN plan_managers.registration_number != 'NULL' THEN plan_managers.registration_number
                   ELSE '9999999999'
            END)
            AS registrationnumber,
            participants.ndis_number,
            DATE_FORMAT(STR_TO_DATE(invoice_details.service_start_date,'%Y-%m-%d'),'%Y.%m.%d') as service_start_date,
            DATE_FORMAT(STR_TO_DATE(invoice_details.service_end_date,'%Y-%m-%d'),'%Y.%m.%d') as service_end_date,
            ndis_pricingguides.support_item_number,
            CASE WHEN '{$this->connection}' = 'plan_on_track' THEN invoices.invoice_number ELSE invoice_details.claim_reference END as reference_number,
            invoice_details.quantity,
            '' as hours,
            invoice_details.unit_price,
            gst.code as gst_code,
            '' as authorizedby,
            '' as participantapproved,
            '' inkindfundingprogram,
            '' as claimtype,
            '' as cancellationreason,
            service_providers.ABN as abn
            "))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'invoices.planmanager_subscriptions_id')
                ->leftJoin('plan_managers', 'plan_managers.id', '=', 'planmanager_subscriptions.plan_manager_id')
                ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
                ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
                ->where('invoices.status', '2')
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->get();
            return response()->json($data);
        }
    }

    public function exporttoqb(Request $request)
    {
        if ($request->ajax()) {


            $invoicelink = 'https://' . config('filesystems.disks.s3AllPublicMedia.bucket') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/';
            $data = "";
            $dataCount = 1;
            $invoice_ids = json_decode($request->invoice_ids);

            $invoice_status_ids = array("5");

            $invoice_only_opened = DB::connection($this->connection)->table('invoices')->whereIn('id', $invoice_ids)->get();


            foreach ($invoice_only_opened as $key => $verify) {

                if (!in_array($verify->status, $invoice_status_ids)) {
                    return response()->json(['success' => false, 'msg' => 'Please check selected items, can only export Open invoices.']);
                    return false;
                }
            }

            $connect = ($this->connection);

            //export selected only
            if (count($invoice_ids) > 0) {
                if (($this->connection) == "plan_on_track") {
                    $data = DB::connection($this->connection)->table('invoices')
                        ->select(DB::raw("         
                    invoices.invoice_number,
                    DATE_ADD(invoices.invoice_date, INTERVAL 3 DAY) as duedate,
                    '' as servicedate,
                    QUOTE(GROUP_CONCAT(invoice_details.description)) as description,
                    service_providers.firstname as supplier,
                    invoices.invoice_date as billdate,
                    plan_managers.qbname as account,
                    SUM(invoice_details.amount) as lineamount,
                    'P1' as linetaxcode,
                    concat('$invoicelink', invoice_documents.s3_filepath, invoice_documents.s3_key) as invoiceurl,
                    '$connect' as connect
                    "))
                        ->leftJoin('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
                        ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
                        ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                        ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'invoices.planmanager_subscriptions_id')
                        ->leftJoin('plan_managers', 'plan_managers.id', '=', 'planmanager_subscriptions.plan_manager_id')
                        ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
                        ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
                        ->leftJoin('invoice_documents', 'invoice_documents.invoice_id', '=', 'invoices.id')
                        ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                        ->where('invoices.status', '5')
                        ->whereIn('invoices.id', $invoice_ids)
                        ->whereNull('invoices.deleted_at')
                        ->whereNull('invoice_details.deleted_at')
                        //->where((DB::raw("Select count(*) where invoice_details.invoice_id = invoices.id")), '>', 1)
                        ->groupBy(
                            'invoices.invoice_number',
                            'invoices.invoice_date',
                            'service_providers.firstname',
                            'service_providers.lastname',
                            'plan_managers.qbname',
                            'invoice_documents.s3_filepath',
                            'invoice_documents.s3_key',
                            'invoices.id'
                        )
                        ->get();
                } else {
                    $data = DB::connection($this->connection)->table('invoice_details')
                        ->select(DB::raw("
            
                    invoices.invoice_number,
                    DATE_ADD(invoices.invoice_date , INTERVAL 3 DAY) as duedate,
                    invoice_details.service_start_date as servicedate,
                    invoice_details.description as description,
                    service_providers.firstname as supplier,
                    invoices.invoice_date as billdate,
                    plan_managers.qbname as account,
                    invoice_details.amount as lineamount,
                    gst.code as linetaxcode,
                    concat('$invoicelink', invoice_documents.s3_filepath , invoice_documents.s3_key) as invoiceurl,
                    '$connect' as connect
                    "))
                        ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                        ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
                        ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                        ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'invoices.planmanager_subscriptions_id')
                        ->leftJoin('plan_managers', 'plan_managers.id', '=', 'planmanager_subscriptions.plan_manager_id')
                        ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
                        ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
                        ->leftJoin('invoice_documents', 'invoice_documents.invoice_id', '=', 'invoices.id')
                        ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                        ->where('invoices.status', '5')
                        ->whereIn('invoices.id', $invoice_ids)
                        ->whereNull('invoices.deleted_at')
                        ->whereNull('invoice_details.deleted_at')
                        ->get();
                }
            }
            //export all opened
            else {

                if (($this->connection) == "plan_on_track") {
                    $data = DB::connection($this->connection)->table('invoices')
                        ->select(DB::raw("         
                    invoices.invoice_number,
                    DATE_ADD(invoices.invoice_date, INTERVAL 3 DAY) as duedate,
                    '' as servicedate,
                    QUOTE(GROUP_CONCAT(invoice_details.description)) as description,
                    service_providers.firstname as supplier,
                    invoices.invoice_date as billdate,
                    plan_managers.qbname as account,
                    SUM(invoice_details.amount) as lineamount,
                    'P1' as linetaxcode,
                    concat('$invoicelink', invoice_documents.s3_filepath, invoice_documents.s3_key) as invoiceurl,
                    '$connect' as connect
                    "))
                        ->leftJoin('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
                        ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
                        ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                        ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'invoices.planmanager_subscriptions_id')
                        ->leftJoin('plan_managers', 'plan_managers.id', '=', 'planmanager_subscriptions.plan_manager_id')
                        ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
                        ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
                        ->leftJoin('invoice_documents', 'invoice_documents.invoice_id', '=', 'invoices.id')
                        ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                        ->where('invoices.status', '5')
                        // ->whereIn('invoices.id', $invoice_ids)
                        ->whereNull('invoices.deleted_at')
                        ->whereNull('invoice_details.deleted_at')
                        //->where((DB::raw("Select count(*) where invoice_details.invoice_id = invoices.id")), '>', 1)
                        ->groupBy(
                            'invoices.invoice_number',
                            'invoices.invoice_date',
                            'service_providers.firstname',
                            'service_providers.lastname',
                            'plan_managers.qbname',
                            'invoice_documents.s3_filepath',
                            'invoice_documents.s3_key',
                            'invoices.id'
                        )
                        ->get();
                } else {
                    $data = DB::connection($this->connection)->table('invoice_details')
                        ->select(DB::raw("
            
                    invoices.invoice_number,
                    DATE_ADD(invoices.invoice_date , INTERVAL 3 DAY) as duedate,
                    invoice_details.service_start_date as servicedate,
                    invoice_details.description as description,
                    service_providers.firstname as supplier,
                    invoices.invoice_date as billdate,
                    plan_managers.qbname as account,
                    invoice_details.amount as lineamount,
                    gst.code as linetaxcode,
                    concat('$invoicelink', invoice_documents.s3_filepath , invoice_documents.s3_key) as invoiceurl,
                    '$connect' as connect
                    "))
                        ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                        ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
                        ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                        ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'invoices.planmanager_subscriptions_id')
                        ->leftJoin('plan_managers', 'plan_managers.id', '=', 'planmanager_subscriptions.plan_manager_id')
                        ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
                        ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
                        ->leftJoin('invoice_documents', 'invoice_documents.invoice_id', '=', 'invoices.id')
                        ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                        ->where('invoices.status', '5')
                        // ->whereIn('invoices.id', $invoice_ids)
                        ->whereNull('invoices.deleted_at')
                        ->whereNull('invoice_details.deleted_at')
                        ->get();
                }
            }


            return response()->json(['success' => true, 'data' => $data]);
            //return view('admin.invoices', compact('data', 'connect'));
        }
    }



    public function loadrecords(Request $request)
    {
        $invoiceDetails = DB::connection($this->connection)->table('invoice_details')
            ->selectRaw(
                '
        invoice_details.amount,
        invoice_details.invoice_id
        '
            )
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
            ->whereNull('invoice_details.deleted_at')
            ->get();

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
                service_providers.ABN as abn,
                invoices.service_provider_acc_number,
                invoice_status.description as status,
                service_providers.firstname as service_provider_first_name,
                service_providers.lastname as service_provider_last_name,
                invoices.remarks,
                participants.firstname as participant_firstname,
                participants.lastname as participant_lastname,
                CONCAT(participants.firstname, " " ,participants.lastname) as participant_name,
                (SELECT SUM(invoice_details.amount) as totalAmount from invoice_details where invoice_details.invoice_id = invoices.id and invoice_details.deleted_at is null) as invoice_amt
                '
                )
                ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
                ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
                ->leftJoin('planmanager_subscriptions', 'planmanager_subscriptions.id', '=', 'invoices.planmanager_subscriptions_id')
                ->leftJoin('invoice_status', 'invoice_status.id', '=', 'invoices.status')
                ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                ->whereNull('invoices.deleted_at')
                ->orderBy('invoices.id', 'desc')
                ->groupBy(
                    'invoices.id',
                    'participants.ndis_number',
                    'invoices.invoice_number',
                    'invoices.invoice_date',
                    'invoices.due_date',
                    'invoices.reference_number',
                    'service_providers.ABN',
                    'invoices.service_provider_acc_number',
                    'invoices.status',
                    'service_providers.firstname',
                    'service_providers.lastname',
                    'invoices.remarks',
                    'participants.firstname',
                    'participants.lastname',
                    'invoice_status.description'
                )->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        // $generated = $this->secure_random_string(8);

        // var_dump($generated);

        $user_id = auth()->user()->id;

        $user_data = DB::connection($this->connection)->table('users')
            ->where('users.id', $user_id)
            ->get()->first();

        $invoice_status = DB::connection($this->connection)->table('invoice_status')->whereIn('id', [2, 4, 5, 6, 7])->get();

        //var_dump($invoiceDetails);
        return view('admin.invoices', compact('user_data', 'invoice_status'));
    }

    private function secure_random_string($length)
    {
        $random_string = '';
        for ($i = 0; $i < $length; $i++) {
            $number = random_int(0, 36);
            $character = base_convert($number, 10, 36);
            $random_string .= $character;
        }

        // $length = 5;

        // $randomletter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 3);

        return $random_string;
    }

    public function sendEmailNotif($oData)
    {
        $email = "";
        foreach ($oData as $row) {
            $row->plan_manager_details = $this->getPlanManagerDetails($row->planmanager_subscriptions_id);
            $email = $row->plan_manager_details->email;
            $row->send_from = ($this->connection == "plan_on_track") ? 'planapp@planontrack.com.au' : 'support@planaji.com';
            $this->runtime_mail_config($this->connection);

            //if remaining budget is 500 or below but not zero
            if (((float)$row->total_remaining_support_item) <= 500 && ((float)$row->total_remaining_support_item) != 0) {
                $this->updatePlanDetailsIsSentEmail($row->plan_detail_id, 1);
                Mail::to($email)->send(new SendNotifLowBudget($row));
            }
            //if remaining budget reached zero
            else if (((float)$row->total_remaining_support_item) == 0) {
                $this->updatePlanDetailsIsSentEmail($row->plan_detail_id, 1);
                Mail::to($email)->send(new AlertZeroBudget($row));
            }
            //if remaining budget is still sufficient
            else { //else if (filter_var($row->is_sent_email, FILTER_VALIDATE_BOOLEAN) == 0) {
                $this->updatePlanDetailsIsSentEmail($row->plan_detail_id);
            }
        }
    }

    public function runtime_mail_config($connection)
    {
        $get_email_configs = get_email_config($connection);
        if ($get_email_configs)
            foreach ($get_email_configs as $key => $value) {
                Config::set('mail.mailers.smtp.' . $key, $value);
            }
    }

    public function updatePlanDetailsIsSentEmail($plan_detail_id, $is_sent_email = 0)
    {
        if ($is_sent_email)
            return DB::connection($this->connection)->table('plan_details')
                ->where('id', $plan_detail_id)
                ->update(['is_sent_email' => 1]);
        else
            return DB::connection($this->connection)->table('plan_details')
                ->where('id', $plan_detail_id)
                ->update(['is_sent_email' => 0]);
    }

    public function getPlanManagerDetails($plan_manager_subscription_id)
    {
        return DB::connection($this->connection)
            ->table('users')
            ->select(
                'users.name',
                'users.email',
                'users.id',
            )
            ->where('users.plan_manager_subscription_id', $plan_manager_subscription_id)
            ->first();
    }

   private function findExistingInvoice($request){

        if($request->route=='Add' || $request->route=='Duplicate'){
            return DB::connection($this->connection)->table('invoices')
            ->whereNull('deleted_at')->where('planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->where('invoice_number', '=', $request->invoice_number)
            ->where('serviceprovider_id', '=', $request->serviceprovider_id)
            ->exists();
        }
        else if($request->route=='Edit' ){
            return DB::connection($this->connection)->table('invoices')
            ->whereNull('deleted_at')->where('planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->where('invoice_number', '=', $request->invoice_number)
            ->where('serviceprovider_id', '=', $request->serviceprovider_id)
            ->where('id', '!=', $request->invoice_id)
            ->exists();
        }

    }

    public function saverecord(Request $request)
    {
        $input = $request->all();
        $hasError = false;

        if ($request->ajax()) {

            $invoice_details_id = json_decode(stripslashes($request->invoice_details_id));
            $stated_item_id = json_decode(stripslashes($request->stated_item_id));
            $description = json_decode(stripslashes($request->description));
            $service_start_date = json_decode(stripslashes($request->service_start_date));
            $service_end_date = json_decode(stripslashes($request->service_end_date));
            $stated_item_quantity = json_decode(stripslashes($request->stated_item_quantity));
            $stated_item_unit_price = json_decode(stripslashes($request->stated_item_unit_price));
            $stated_item_gst_code = json_decode(stripslashes($request->stated_item_gst_code));
            $stated_item_budget = json_decode(stripslashes($request->stated_item_budget));
            $hours = json_decode(stripslashes($request->hours));
            $claim_type = json_decode(stripslashes($request->claim_type));
            $cancellation_reason = json_decode(stripslashes($request->cancellation_reason));
            $claim_reference = json_decode(stripslashes($request->claim_reference));


            $invoice_id = 0;

            if (    empty($request->serviceprovider_id) 
                    || empty($request->invoice_number) 
                    || empty($request->planmanager_subscriptions_id) 
                    || empty($request->service_provider_ABN) 
            ) {
                return response()->json(['has_error' => true, 'msg' => 'Missing required field/s']);
                return false;
            }

            $isExist = $this->findExistingInvoice($request); // UPDATE: CHECKER FOR EXISTING INVOICE BY CURRENT LOGIN PLAN MANAGER, INVOICE NO, SERVICE PRODIVDER ID - 2020-11-07
            
            if($isExist){
                return response()->json(['has_error' => true, 'msg' => 'Record already exists!']);
                return false;
            }
            else {
                $invoice_details = DB::connection($this->connection)->table('invoice_details')
                    ->where('invoice_details.invoice_id', $request->invoice_id)
                    ->get();

                $get_existing_invoice_details_id = [];

                //get existing support items id
                foreach ($invoice_details as $key => $item) {
                    array_push($get_existing_invoice_details_id, $item->id);
                }

                //delete rows that are not found in the existing
                $delete = array_diff($get_existing_invoice_details_id, $invoice_details_id);
                if (!empty($delete)) {
                    foreach ($delete as $key => $id) {
                        // DB::connection($this->connection)->table('invoice_details')->where('id', '=', $id)->delete();
                        DB::connection($this->connection)->table('invoice_details')
                            ->where('id', $id)
                            ->update(['deleted_at' => now()]);
                    }
                }


                if ($request->route == "Add" || $request->route == "Edit") {

                    $invoice_date = ''; // added variable;

                    $invoice_id = !empty($request->invoice_id) ? intval($request->invoice_id) : 0;


                    $isNewRecord = !empty($request->invoice_id) ? false : true;

                    $isExistRow = DB::connection($this->connection)->table('invoices')->where('id', '=', $invoice_id)->where('serviceprovider_id', '=', $request->serviceprovider_id)->get()->first(); // search single invoice in invoices table;

                    if (!empty($isExistRow)) { // if found, then remain still invoice_date to be encoded again, and update saving method.
                        $invoice_date = $isExistRow->invoice_date; // BUG RESOLVED: GET FIRST ROW RESULT BCOZ THERE IS NO request->invoice_date from ajax request.
                    } else { // for new creation and not found invoices table, will automatic set to present date.
                        $invoice_date = date('Y-m-d');
                    }

                    $this->Invoices->connection = $this->connection;
                    $invoices = $this->Invoices->updateOrCreate(
                        ['id' => $invoice_id],
                        [
                            'planmanager_subscriptions_id' => auth()->user()->plan_manager_subscription_id,
                            'participant_id' => $request->participant_id,
                            'invoice_date' => $invoice_date,
                            'due_date' => $request->due_date,
                            'invoice_number' => $request->invoice_number,
                            'reference_number' => $request->reference_number,

                            //UPDATE: ON FIRST CREATION, REQUIRED THESE FIELDS - 2022-11-07
                            'serviceprovider_id' => $request->serviceprovider_id,
                            // CLOSING UPDATE - 2022-11-07

                            'status' => $request->status
                        ]
                    );


                    $invoice_id = $invoices->id;
                    $this->InvoiceLinkemails->connection = $this->connection;
                    $invoice_linkEmail = $this->InvoiceLinkemails->updateOrCreate(
                        ['id' => $request->invoice_linkemails_id],
                        [
                            'invoice_id' => $invoices->id,
                            'invoice_email_id' => $request->invoice_email_id
                        ]
                    );
                    //$key = 0;


                    $length = count($stated_item_id);
                    // var_dump($length);


                    $this->recalculatePlanBudget($request->participant_id, ($request->route == "Edit" ? $invoice_details_id : []));

                    for ($key = 0; $key < $length; $key++) {

                        // var_dump($key);

                        $getCurrentRemainingBudget = DB::connection($this->connection)
                            ->table('plans')
                            ->select('ndis_pricingguides.id', 'plan_details.id as plan_detail_id', 'plan_details.remaining_budget', 'plan_details.has_quarantine_fund', 'participants.planmanager_subscriptions_id as planmanager_subscriptions_id', 'plan_details.is_sent_email')
                            ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                            ->leftJoin('participants', 'participants.id', '=', 'plans.participant_id')
                            ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                            ->where('plans.participant_id', '=', $request->participant_id)
                            ->where('ndis_pricingguides.id', '=', $stated_item_id[$key])
                            ->where('plan_details.participant_serviceproviders_id', '=', $request->serviceprovider_id)
                            ->where('plan_details.has_quarantine_fund', '=', 1)
                            ->whereNull('plans.deleted_at')
                            ->whereNull('plan_details.deleted_at')
                            ->get();

                        if (count($getCurrentRemainingBudget) < 1) {

                            $item_supportcat = DB::connection($this->connection)
                                ->table('ndis_pricingguides')
                                ->selectRaw('plan_supportreference.support_categories_id, plan_supportreference.id')
                                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                                ->where('ndis_pricingguides.id', '=', $stated_item_id[$key])
                                ->get()->first();

                            $getCurrentRemainingBudget = DB::connection($this->connection)
                                ->table('plans')
                                ->select('ndis_pricingguides.id', 'plan_details.id as plan_detail_id', 'plan_details.remaining_budget', 'plan_details.has_quarantine_fund', 'participants.planmanager_subscriptions_id as planmanager_subscriptions_id', 'plan_details.is_sent_email')
                                ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                                ->leftJoin('participants', 'participants.id', '=', 'plans.participant_id')
                                ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                                ->where('plans.participant_id', '=', $request->participant_id)
                                ->where('ndis_pricingguides.id', '=', $stated_item_id[$key])
                                ->where('plan_details.has_quarantine_fund', '=', 0)
                                ->where('plan_supportreference.support_categories_id', '=', $item_supportcat->support_categories_id)
                                ->whereNull('plans.deleted_at')
                                ->whereNull('plan_details.deleted_at')
                                ->get();

                            if (count($getCurrentRemainingBudget) < 1) {

                                $getCurrentRemainingBudget = DB::connection($this->connection)
                                    ->table('plans')
                                    ->select('plan_details.remaining_budget', 'plan_details.id as plan_detail_id', 'participants.planmanager_subscriptions_id as planmanager_subscriptions_id', 'plan_details.is_sent_email')
                                    ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                                    ->leftJoin('participants', 'participants.id', '=', 'plans.participant_id')
                                    ->where('plan_details.has_stated_item', '=', 0)
                                    ->where('plan_details.has_quarantine_fund', '=', 0)
                                    ->where('plan_details.plan_supportreference_id', '=', $item_supportcat->id)
                                    ->where('plans.participant_id', '=', $request->participant_id)
                                    ->whereNull('plans.deleted_at')
                                    ->whereNull('plan_details.deleted_at')
                                    ->get();
                            }
                        }


                        foreach ($getCurrentRemainingBudget as $key1 => $item1) {
                            if ($item1->remaining_budget < $stated_item_budget[$key]) {
                                $hasError = true;

                                // Log::info($item1->remaining_budget);
                                // Log::info($stated_item_budget[$key]);
                                if ($isNewRecord) {
                                    $this->Invoices->connection = $this->connection;
                                    DB::connection($this->connection)->table('invoices')
                                        ->where('id', $invoice_id)
                                        ->update(['deleted_at' => now()]);
                                }

                                return response()->json(['has_error' => true, 'insufficient_budget' => true, 'msg' => 'Insufficient remaining budget']);
                                return false;
                                break;
                            } else {
                                $hasError = false;
                            }
                        }


                        if (!$hasError) {

                            //UPDATE: NO NEED TO UPDATE INVOICE IF SHOULD HAVE ADD LINE - 2022-12-07

                            // $this->Invoices->connection = $this->connection;
                            // $invoices = $this->Invoices->updateOrCreate(
                            //     ['id' => $invoice_id],
                            //     [
                            //         'serviceprovider_id' => $request->serviceprovider_id,
                            //         'service_provider_ABN' => $request->service_provider_ABN,
                            //         'service_provider_acc_number' => $request->service_provider_acc_number,
                            //         'service_provider_email' => $request->service_provider_email,
                            //     ]
                            // );
                            // CLOSING UPDATE - 2022-12-07

                            $this->InvoiceDetails->connection = $this->connection;
                            $generated_ref = $this->secure_random_string(20) . $invoice_details_id[$key];
                            $this->InvoiceDetails->updateOrCreate(
                                ['id' => $invoice_details_id[$key]],
                                [
                                    'invoice_id' => $invoice_id,
                                    'ndis_pricingguide_id' => $stated_item_id[$key],
                                    'description' => $description[$key],
                                    'service_start_date' => $service_start_date[$key],
                                    'service_end_date' => $service_end_date[$key],
                                    'quantity' => $stated_item_quantity[$key],
                                    'unit_price' => $stated_item_unit_price[$key],
                                    'gst_code' => $stated_item_gst_code[$key],
                                    'amount' => $stated_item_budget[$key],
                                    'hours' => $hours[$key],
                                    'claim_type_id' => $claim_type[$key],
                                    'cancellation_reason_id' => $cancellation_reason[$key],
                                    'capped_price' => 'No',
                                    'payment_request_status' => 'N/A',
                                    'error_message' => "",
                                    'claim_reference' => (!empty($claim_reference[$key])) ? $claim_reference[$key] : $generated_ref
                                ]
                            );
                        } else {
                            return false;
                        }
                    }

                    // For updating remaining budget in participants/plans/3 C's

                    $invoice = DB::connection($this->connection)
                        ->table('invoices')
                        ->selectRaw('invoices.id, invoices.participant_id, invoice_details.ndis_pricingguide_id, invoice_details.amount')
                        ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                        ->where('invoices.id', '=', $invoice_id)->get();

                    $this->triggerSendingNotifEmail($request->participant_id);

                    // $invoice->

                    // var_dump($invoice);
                    // $remainingBudget = 0;
                    // foreach ($invoice as $key => $item) {


                    //     $totalBudget = DB::connection($this->connection)
                    //         ->table('plans')
                    //         ->selectRaw('plans.id, plan_details.id as plan_detail_id, plan_details.category_budget')
                    //         ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                    //         ->where('plans.participant_id', $item->participant_id)
                    //         ->where('plan_details.plan_supportreference_id', $item->ndis_pricingguide_id)
                    //         ->whereNull('plans.deleted_at')
                    //         ->whereNull('plan_details.deleted_at')
                    //         ->get()->first();

                    //     $remainingBudget = ((float)$totalBudget->category_budget) - ((float)$item->amount);

                    // var_dump($stated_item_budget);
                    // var_dump($item->amount);
                    // var_dump($totalBudget->category_budget);
                    // var_dump($remainingBudget);


                    //     $invoice = DB::connection($this->connection)
                    //         ->table('plans')
                    //         ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                    //         ->where('plans.participant_id', $item->participant_id)
                    //         ->where('plan_details.plan_supportreference_id', $item->ndis_pricingguide_id)
                    //         ->whereNull('plans.deleted_at')
                    //         ->whereNull('plan_details.deleted_at')
                    //         ->update(['plan_details.remaining_budget' =>  $remainingBudget]);
                    // }
                } else if ($request->route == "Duplicate") {

                    $this->Invoices->connection = $this->connection;
                    $invoices = $this->Invoices->updateOrCreate(
                        ['id' => 0],
                        [
                            'planmanager_subscriptions_id' => auth()->user()->plan_manager_subscription_id,
                            'participant_id' => $request->participant_id,
                            'invoice_date' => $request->invoice_date, // as a appeared as this $request->invoice_date also equipped present date. 
                            'due_date' => $request->due_date,
                            'invoice_number' => $request->invoice_number,
                            'reference_number' => $request->reference_number,

                            //UPDATE: ON FIRST CREATION, REQUIRED THESE FEILDS - 2022-12-07
                            'serviceprovider_id' => $request->serviceprovider_id,
                            // 'service_provider_ABN' => $request->service_provider_ABN,
                            // 'service_provider_acc_number' => $request->service_provider_acc_number,
                            // 'service_provider_email' => $request->service_provider_email,

                            'status' => '1' //new
                        ]
                    );

                    $invoice_id = !empty($request->invoice_id) ? $request->invoice_id : $invoices->id;

                    $this->recalculatePlanBudget($request->participant_id, []);

                    foreach ($stated_item_id as $key => $id) {

                        $getCurrentRemainingBudget = DB::connection($this->connection)
                            ->table('plans')
                            ->select('ndis_pricingguides.id', 'plan_details.remaining_budget')
                            ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                            ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                            ->where('plans.participant_id', '=', $request->participant_id)
                            ->where('ndis_pricingguides.id', '=', $stated_item_id[$key])
                            ->where('plan_details.participant_serviceproviders_id', '=', $request->serviceprovider_id)
                            ->where('plan_details.has_quarantine_fund', '=', 1)
                            ->whereNull('plans.deleted_at')
                            ->whereNull('plan_details.deleted_at')
                            ->get();


                        if (count($getCurrentRemainingBudget) < 1) {

                            // $item_supportcat = DB::connection($this->connection)
                            //     ->table('plans')
                            //     ->select('plan_supportreference.support_categories_id')
                            //     ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                            //     ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                            //     ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                            //     ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                            //     ->where('plans.participant_id', '=', $request->participant_id)
                            //     ->where('ndis_pricingguides.id', '=', $stated_item_id[$key])
                            //     ->where('plan_details.has_quarantine_fund', '=', 0)
                            //     ->whereNull('plans.deleted_at')
                            //     ->whereNull('plan_details.deleted_at')
                            //     ->get()
                            //     ->first();

                            $item_supportcat = DB::connection($this->connection)
                                ->table('ndis_pricingguides')
                                ->selectRaw('plan_supportreference.support_categories_id, plan_supportreference.id')
                                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                                ->where('ndis_pricingguides.id', '=', $stated_item_id[$key])
                                ->get()->first();

                            $getCurrentRemainingBudget = DB::connection($this->connection)
                                ->table('plans')
                                ->select('ndis_pricingguides.id', 'plan_details.remaining_budget', 'plan_details.has_quarantine_fund')
                                ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                                ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                                ->where('plans.participant_id', '=', $request->participant_id)
                                ->where('ndis_pricingguides.id', '=', $stated_item_id[$key])
                                ->where('plan_details.has_quarantine_fund', '=', 0)
                                ->where('plan_supportreference.support_categories_id', '=', $item_supportcat->support_categories_id)
                                ->whereNull('plans.deleted_at')
                                ->whereNull('plan_details.deleted_at')
                                ->get();

                            if (count($getCurrentRemainingBudget) < 1) {
                                $getCurrentRemainingBudget = DB::connection($this->connection)
                                    ->table('plans')
                                    ->select('plan_details.remaining_budget')
                                    ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
                                    ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                                    ->where('plan_details.has_stated_item', '=', 0)
                                    ->where('plan_details.has_quarantine_fund', '=', 0)
                                    ->where('plan_details.plan_supportreference_id', '=', $item_supportcat->id)
                                    ->where('plans.participant_id', '=', $request->participant_id)
                                    ->whereNull('plans.deleted_at')
                                    ->whereNull('plan_details.deleted_at')
                                    ->get();
                            }
                        }


                        foreach ($getCurrentRemainingBudget as $key1 => $item1) {
                            if ($item1->remaining_budget < $stated_item_budget[$key]) {
                                $hasError = true;

                                $this->Invoices->connection = $this->connection;
                                DB::connection($this->connection)->table('invoices')
                                    ->where('id', $invoice_id)
                                    ->update(['deleted_at' => now()]);


                                return response()->json(['has_error' => true, 'insufficient_budget' => true, 'msg' => 'Insufficient remaining budget']);
                                return false;
                                break;
                            } else {
                                $hasError = false;
                            }
                        }

                        //UPDATE: NO NEED TO UPDATE INVOICE IF SHOULD HAVE ADD LINE - 2020-11-07
                        // $this->Invoices->connection = $this->connection;
                        // $invoices = $this->Invoices->updateOrCreate(
                        //     ['id' => $invoice_id],
                        //     [
                        //         'serviceprovider_id' => $request->serviceprovider_id,
                        //         'service_provider_ABN' => $request->service_provider_ABN,
                        //         'service_provider_acc_number' => $request->service_provider_acc_number,
                        //         'service_provider_email' => $request->service_provider_email,
                        //     ]
                        // );
                        // CLOSING UPDATE - 2020-11-07


                        $this->InvoiceDetails->connection = $this->connection;
                        $generated_ref = $this->secure_random_string(20) . $invoice_details_id[$key];
                        $this->InvoiceDetails->updateOrCreate(
                            ['id' => 0],
                            [
                                'invoice_id' => $invoice_id,
                                'ndis_pricingguide_id' => $id,
                                'description' => $description[$key],
                                'service_start_date' => $service_start_date[$key],
                                'service_end_date' => $service_end_date[$key],
                                'quantity' => $stated_item_quantity[$key],
                                'unit_price' => $stated_item_unit_price[$key],
                                'gst_code' => $stated_item_gst_code[$key],
                                'amount' => $stated_item_budget[$key],
                                'hours' => $hours[$key],
                                'claim_type_id' => $claim_type[$key],
                                'cancellation_reason_id' => $cancellation_reason[$key],
                                'claim_reference' => $generated_ref
                            ]
                        );
                    }
                    $this->InvoiceLinkemails->connection = $this->connection;
                    $this->InvoiceLinkemails->updateOrCreate(
                        ['id' => 0],
                        [
                            'invoice_id' => $invoice_id,
                            'invoice_email_id' => $request->invoice_email_id
                        ]
                    );

                    $this->triggerSendingNotifEmail($request->participant_id);
                } else {
                    return false; // view route or something else.
                }



                return response()->json(['has_error' => false, 'msg' => 'Record saved successfully.', 'route' => $request->route, 'return_id' => $invoice_id]);
            }
        }
    }

    public function triggerSendingNotifEmail($participant_id)
    {

        $this->recalculatePlanBudget($participant_id, []);
        $getCurrentRemainingBudget = DB::connection($this->connection)
            ->table('plans')
            ->select(
                'participants.firstname',
                'participants.lastname',
                'participants.ndis_number',
                'support_purposes.purpose as support_name',
                'outcome_domains.outcome_domain',
                'support_categories.support_category as support_category',
                'plan_details.category_budget as total_budget_support_item',
                'plan_details.id as plan_detail_id',
                'plan_details.remaining_budget as total_remaining_support_item',
                'participants.planmanager_subscriptions_id as planmanager_subscriptions_id',
                'plan_details.is_sent_email',
                'plans.deleted_at as send_from' //temp string for storage
            )
            ->leftJoin('plan_details', 'plans.id', '=', 'plan_details.plan_id')
            ->leftJoin('participants', 'participants.id', '=', 'plans.participant_id')
            ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
            ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
            ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
            ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
            ->where('plans.participant_id', '=', $participant_id)
            ->whereNull('plans.deleted_at')
            ->whereNull('plan_details.deleted_at')
            ->get();


        $this->sendEmailNotif($getCurrentRemainingBudget);
    }


    public function recalculatePlanBudget($participant_id, $itemIds)
    {

        $plan = DB::connection($this->connection)
            ->table('plans')
            ->selectRaw('id')
            ->where('plans.participant_id', '=', $participant_id)
            ->where('plans.status', '=', "Active")
            ->whereNull('plans.deleted_at')
            ->first();

        if (empty($plan)) {
            return false;
        }

        $this->PlanDetail->connection = $this->connection;

        $planDetails = DB::connection($this->connection)
            ->table('plan_details')
            ->selectRaw('plan_details.id, category_budget, remaining_budget, has_quarantine_fund, participant_serviceproviders_id')
            ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
            ->where('plans.participant_id', '=', $participant_id)
            ->whereNull('plans.deleted_at')
            ->whereNull('plan_details.deleted_at')
            ->get();

        foreach ($planDetails as $key => $plandetail) {

            $detail = DB::connection($this->connection)
                ->table('plan_details')
                ->where('plan_details.id', $plandetail->id)
                ->whereNull('plan_details.deleted_at')
                ->update(['plan_details.remaining_budget' =>  $plandetail->category_budget]);
        }



        $coreInvoices = DB::connection($this->connection)
            ->table('invoice_details')
            ->selectRaw('invoices.id, invoice_details.id as invoicedetail_id, invoices.participant_id, invoice_details.ndis_pricingguide_id, invoice_details.amount as amount, invoices.serviceprovider_id, plan_supportreference.support_categories_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
            ->where('invoices.participant_id', '=', $participant_id)
            ->where('plan_supportreference.support_purposes_id', '=', 1)
            ->whereNull('invoice_details.deleted_at')
            ->whereNotIn('invoice_details.id', $itemIds)
            ->whereNull('invoices.deleted_at')
            ->distinct()
            ->get();



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
                ->where('plan_details.plan_id', '=', $plan->id)
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
                ->where('plan_details.plan_id', '=', $plan->id)
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
                ->where('plan_details.plan_id', '=', $plan->id)
                ->distinct()
                ->get();

            foreach ($planDetailWithQFs as $key => $planDetailWithQF) {
                if (
                    $planDetailWithQF->ndis_pricingguides_id == $coreInvoice->ndis_pricingguide_id &&
                    $planDetailWithQF->participant_serviceproviders_id == $coreInvoice->serviceprovider_id
                ) {

                    DB::connection($this->connection)
                        ->table('plan_details')
                        ->where('plan_details.id', $planDetailWithQF->id)
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
                            ->where('plan_details.id', $planDetailWithStatedItem->id)
                            ->whereNull('plan_details.deleted_at')
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
                                ->where('plan_details.id', $planDetailWithNoQF->id)
                                ->whereNull('plan_details.deleted_at')
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

        $capitalInvoices = DB::connection($this->connection)
            ->table('invoice_details')
            ->selectRaw('invoices.id, invoice_details.id as invoicedetails_id, invoices.participant_id, invoice_details.ndis_pricingguide_id, invoice_details.amount as amount, invoices.serviceprovider_id, plan_supportreference.support_categories_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
            ->where('invoices.participant_id', '=', $participant_id)
            ->where('plan_supportreference.support_purposes_id', '=', 2)
            ->whereNull('invoice_details.deleted_at')
            ->whereNull('invoices.deleted_at')
            ->whereNotIn('invoice_details.id', $itemIds)
            ->distinct()
            ->get();


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
                ->where('plan_details.plan_id', '=', $plan->id)
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
                ->where('plan_details.plan_id', '=', $plan->id)
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
                ->where('plan_details.plan_id', '=', $plan->id)
                ->distinct()
                ->get();

            foreach ($planDetailWithQFs as $key => $planDetailWithQF) {

                if (
                    $planDetailWithQF->ndis_pricingguides_id == $capitalInvoice->ndis_pricingguide_id &&
                    $planDetailWithQF->participant_serviceproviders_id == $capitalInvoice->serviceprovider_id
                ) {

                    DB::connection($this->connection)
                        ->table('plan_details')
                        ->where('plan_details.id', $planDetailWithQF->id)
                        ->where('plan_details.participant_serviceproviders_id', $capitalInvoice->serviceprovider_id)
                        ->whereNull('plan_details.deleted_at')
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
                            ->where('plan_details.id', $planDetailWithStatedItem->id)
                            ->whereNull('plan_details.deleted_at')
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
                                ->where('plan_details.id', $planDetailWithNoQF->id)
                                ->whereNull('plan_details.deleted_at')
                                ->update(['plan_details.remaining_budget' =>  $planDetailWithNoQF->remaining_budget - $capitalInvoice->amount]);

                            $deducted = true;
                            break;
                        }
                    }
                }
            }
        }

        $capacityInvoices = DB::connection($this->connection)
            ->table('invoice_details')
            ->selectRaw('invoices.id, invoice_details.id as invoicedetails_id, invoices.participant_id, invoice_details.ndis_pricingguide_id, invoice_details.amount as amount, invoices.serviceprovider_id, plan_supportreference.support_categories_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
            ->where('invoices.participant_id', '=', $participant_id)
            ->where('plan_supportreference.support_purposes_id', '=', 3)
            ->whereNull('invoice_details.deleted_at')
            ->whereNotIn('invoice_details.id', $itemIds)
            ->whereNull('invoices.deleted_at')
            ->distinct()
            ->get();

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
                ->where('plan_details.plan_id', '=', $plan->id)
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
                ->where('plan_details.plan_id', '=', $plan->id)
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
                ->where('plan_details.plan_id', '=', $plan->id)
                ->distinct()
                ->get();

            foreach ($planDetailWithQFs as $key => $planDetailWithQF) {
                if (
                    $planDetailWithQF->ndis_pricingguides_id == $capacityInvoice->ndis_pricingguide_id &&
                    $planDetailWithQF->participant_serviceproviders_id == $capacityInvoice->serviceprovider_id
                ) {

                    DB::connection($this->connection)
                        ->table('plan_details')
                        ->where('plan_details.id', $planDetailWithQF->id)
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
                            ->where('plan_details.id', $planDetailWithStatedItem->id)
                            ->whereNull('plan_details.deleted_at')
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
                                ->where('plan_details.id', $planDetailWithNoQF->id)
                                ->whereNull('plan_details.deleted_at')
                                ->update(['plan_details.remaining_budget' =>  $planDetailWithNoQF->remaining_budget - $capacityInvoice->amount]);

                            if ($planDetailWithNoQF->id)
                                $deducted = true;
                            break;
                        }
                    }
                }
            }
        }
    }

    public function editrecord($id)
    {
        // $invoices = Invoices::find($id);
        // return response()->json($invoices);

        $invoices = DB::connection($this->connection)->table('invoices')
            ->selectRaw(
                "
            invoices.id,
            participants.ndis_number,
            invoices.invoice_number,
            invoices.invoice_date,
            invoices.due_date,
            invoices.reference_number,
            service_providers.ABN as abn,
            invoices.service_provider_acc_number,
            invoices.status,
            service_providers.firstname as service_provider_first_name,
            service_providers.lastname as service_provider_last_name,
            invoices.serviceprovider_id,
            invoices.status,
            invoices.remarks,
            participants.id as participant_id
            "
            )
            ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
            ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
            ->whereNull('invoices.deleted_at')
            ->where('invoices.id', '=', $id)
            ->get();

        $invoice_details = DB::connection($this->connection)->table('invoice_details')
            ->selectRaw(
                "
            invoice_details.*,
            ndis_pricingguides.*,
            invoice_details.id
            "
            )
            ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
            ->whereNull('invoice_details.deleted_at')
            ->where('invoice_details.invoice_id', '=', $id)
            ->get();

        $invoice_linkEmails = DB::connection($this->connection)->table('invoice_linkemails')
            ->selectRaw(
                "invoice_linkemails.id as id,
            invoice_linkemails.*,
            invoice_emails.subject,
            invoice_emails.from_email
            "
            )
            ->leftJoin('invoice_emails', 'invoice_emails.id', '=', 'invoice_linkemails.invoice_email_id')
            ->whereNull('invoice_linkemails.deleted_at')
            ->where('invoice_linkemails.invoice_id', '=', $id)
            ->first();

        // var_dump($invoice_linkEmails);
        $response = array('invoices' => $invoices[0], 'invoice_details' => $invoice_details, 'invoice_linkemails' => $invoice_linkEmails);



        // $response = array('invoices' => $invoices[0], 'invoice_details' => $invoice_details);

        return response()->json($response);


        //return response()->json($data);
    }

    public function getoutparticipant(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('participants')
                ->join('invoices', 'invoices.id', '=', 'plan_supportreference.outcome_domains_id')
                ->whereNull('participants.deleted_at')
                ->where('plan_supportreference.support_categories_id', '=', $request->support_categories_id)
                ->where('planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)->get();
            return response()->json($data);
        }
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


            // DB::connection($this->connection)->table('invoice_documents')->where([
            //     ['invoice_id', '=', $id]
            // ])->delete();

            // DB::connection($this->connection)->table('invoice_details')->where([
            //     ['invoice_id', '=', $id]
            // ])->delete();

            // DB::connection($this->connection)->table('invoices')->where('id', '=', $id)->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)->delete();

            DB::connection($this->connection)->table('invoice_documents')
                ->where('invoice_id', $id)
                ->update(['deleted_at' => now()]);

            DB::connection($this->connection)->table('invoice_details')
                ->where('id', $id)
                ->update(['deleted_at' => now()]);

            DB::connection($this->connection)->table('invoices')
                ->where('id', $id)
                ->where('planmanager_subscriptions_id', '=', $plan_manager_subscription_id)
                ->update(['deleted_at' => now()]);

            return response()->json(['success' => 'Record deleted successfully.' . $id]);
        }
    }

    public function getsupportitems(Request $request)
    {

        $id = $request->id;

        $data = DB::connection($this->connection)->table('invoice_details')->where([
            ['invoice_id', '=', $id]
        ]);

        return view('admin.participantsplan');
    }


    public function getsinglestateditem(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            // var_dump($id);
            $data = DB::connection($this->connection)->table('invoice_details')->where('id', '=', $id)->get()->first();


            return response()->json(['success' => 'Loaded successfully.', 'data' => $data]);
        }
    }

    public function validateinvoices()
    {

        $remarks1 = [];
        $remarks2 = [];
        $remarks3 = [];

        $participants_with_plans = [];

        $status = '3'; // unverified
        $remark1message = "Invoice date not within NDIS date range";
        $remark2message = "No active plan";
        $remark3message = "Invoice is expired";

        $expirationdate = '';
        $remarks = '';

        date_default_timezone_set('Asia/Manila');
        $today = Date('Y-m-d');


        $invoices = DB::connection($this->connection)->table('invoices')
            ->selectRaw('
                    invoices.id,
                    invoices.invoice_date,
                    participants.id as participant_id,
                    participants.ndis_plan_start_date as ndis_plan_start_date,
                    participants.ndis_plan_end_date as ndis_plan_end_date,
                    invoices.remarks
                ')
            ->join('participants', 'participants.id', '=', 'invoices.participant_id')
            ->whereIn('invoices.status', ['1', '3'])
            ->whereNull('invoices.deleted_at')
            ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
            ->get();


        $plans = DB::connection($this->connection)->table('plans')
            ->selectRaw('
                plans.participant_id
            ')
            ->whereNull('plans.deleted_at')
            ->get();


        foreach ($plans as $key => $plan_item) {

            array_push($participants_with_plans, $plan_item->participant_id);
        }


        foreach ($invoices as $key => $item) {

            $this->Invoices->connection = $this->connection;
            $this->Invoices->updateOrCreate(
                ['id' => $item->id],
                [
                    'status' => '2', //verified
                    'remarks' => "N/A"
                ]
            );

            //first validation
            $plan_start_date = Date('Y-m-d', strtotime($item->ndis_plan_start_date));
            $plan_end_date = Date('Y-m-d', strtotime($item->ndis_plan_end_date));

            if (($item->invoice_date < $plan_start_date) || ($item->invoice_date > $plan_end_date)) {
                array_push($remarks1, $item->id);
            }


            //second validation
            if (!in_array($item->participant_id, $participants_with_plans)) {
                array_push($remarks2, $item->id);
            }

            $expirationdate = Date('Y-m-d', strtotime($today . '-3 days'));

            //third validation
            if ($item->invoice_date < $expirationdate) {
                array_push($remarks3, $item->id);
            }
        }


        foreach ($remarks1 as $key1 => $item1) {
            $this->Invoices->connection = $this->connection;
            $this->Invoices->updateOrCreate(
                ['id' => $item1],
                [
                    'status' => $status,
                    'remarks' => $remark1message,
                ]
            );
        }

        foreach ($remarks2 as $key2 => $item2) {

            $remarks = $remark2message;

            if (in_array($item2, $remarks1)) {
                $remarks = $remark1message . '/ ' . $remark2message;
            }
            $this->Invoices->connection = $this->connection;
            $this->Invoices->updateOrCreate(
                ['id' => $item2],
                [
                    'status' => $status,
                    'remarks' => $remarks
                ]
            );
        }

        foreach ($remarks3 as $key3 => $item3) {

            $remarks = $remark3message;

            if (in_array($item3, $remarks1)) {
                $remarks = $remark1message . '/ ' . $remark3message;
            }
            if (in_array($item3, $remarks2)) {
                $remarks = $remark2message . '/ ' . $remark3message;
            }
            if ((in_array($item3, $remarks2)) && (in_array($item3, $remarks3))) {
                $remarks = $remark1message . '/ ' . $remark2message . '/ ' . $remark3message;
            }

            $this->Invoices->connection = $this->connection;
            $this->Invoices->updateOrCreate(
                ['id' => $item3],
                [
                    'status' => $status,
                    'remarks' => $remarks
                ]
            );
        }


        return response()->json(['success' => true, 'msg' => 'Records validated successfully.']);
    }

    public function updateinvoicestatus(Request $request)
    {
        $invoice_ids = json_decode($request->invoice_ids);
        $invoice_status_id = ($request->invoice_status_id);

        $invoice_only_verified = DB::connection($this->connection)->table('invoices')->whereIn('id', $invoice_ids)->get();


        // foreach($invoice_only_verified as $key => $verify){
        //     if($verify->status != 2 || $verify->status != 1){
        //         return response()->json(['success' => false, 'msg' => 'Please check invoices, only verified can update.']);
        //         return false;
        //     }
        // }

        $invoice_status_ids = array("2", "3", "4", "5", "6", "7");


        foreach ($invoice_only_verified as $key => $verify) {

            if (!in_array($verify->status, $invoice_status_ids)) {
                return response()->json(['success' => false, 'msg' => 'Please check selected items, cannot update unverified invoices']);
                return false;
            }
        }

        foreach ($invoice_ids as $key => $id) {
            $this->Invoices->connection = $this->connection;
            $this->Invoices->updateOrCreate(
                ['id' => $id],
                [
                    'status' => $invoice_status_id
                ]
            );
        }
        return response()->json(['success' => true, 'msg' => 'Invoice Update Successfully.']);
    }

    public function hidepaidinvoices(Request $request)
    {

        if ($request->ajax()) {
            $this->User->connection = $this->connection;

            $user = $this->User->updateOrCreate(
                ['id' => auth()->user()->id],
                [
                    'hide_paid_invoices' => filter_var($request->hidepaidinvoices, FILTER_VALIDATE_BOOLEAN),
                ]
            );

            return response()->json(['success' => true, 'msg' => 'Successfully updated']);
        }
    }

    public function savepagelength(Request $request)
    {
        if ($request->ajax()) {
            $this->User->connection = $this->connection;

            $user = $this->User->updateOrCreate(
                ['id' => auth()->user()->id],
                [
                    'page_length' => $request->page_length
                ]
            );

            return response()->json(['success' => true, 'msg' => 'Successfully updated']);
        }
    }

    public function setunitprice(Request $request)
    {
        if ($request->ajax()) {

            $participantdata = DB::connection($this->connection)->table('participants')
                ->selectRaw(
                    'Upper(participants.state) as state'
                )
                ->where('participants.id', '=', $request->participant_id)->get()->first();

            $state = 'NSW';

            if ($participantdata) {
                $state = $participantdata->state;
            }

            $data = DB::connection($this->connection)->table('ndis_pricingguides')
                ->selectRaw(
                    "CASE
                        WHEN '{$state}' = 'ACT' THEN ACT
                        WHEN '{$state}' = 'NSW' THEN NSW
                        WHEN '{$state}' = 'NT' THEN NT
                        WHEN '{$state}' = 'QLD' THEN QLD
                        WHEN '{$state}' = 'SA' THEN SA
                        WHEN '{$state}' = 'TAS' THEN TAS
                        WHEN '{$state}' = 'VIC' THEN VIC
                        WHEN '{$state}' = 'WA' THEN WA
                        ELSE NSW
                    END as unit_price"
                )
                ->where('ndis_pricingguides.id', '=', $request->id)->get();
            return response()->json($data[0]->unit_price);
        }
    }

    /**Upload Invoice  */
    public function uploadInvoice(Request $request)
    {
        if ($request->ajax()) {
            $invoice_document = DB::connection($this->connection)->table('invoice_documents')->where('invoice_id', $request->invoice_id)->first();
            if ($request->file('file')) {
                $host = $request->getHttpHost();
                $file = $request->file;
                $fileName = $request->file->getClientOriginalName();
                $originalName = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExt = $request->file->extension();
                $uniqueS3DocName =  Str::uuid() . '.' . $fileExt;
                if ($host == env('PLANAJI_HOST')) {
                    $filePath =  'planaji/invoice/';
                } elseif ($host == env('PLANONTRACK_HOST')) {
                    $filePath =  'plan_on_track/invoice/';
                } else {
                    $filePath = 'temp/invoice/';
                }
                $fileKey = $filePath . $uniqueS3DocName;
                try {
                    if ($invoice_document) {
                        $path = Storage::disk('s3AllPublicMedia')->put($fileKey, file_get_contents($file));
                        $document = DB::connection($this->connection)
                            ->table('invoice_documents')
                            ->where('id', $invoice_document->id)
                            ->update([
                                'file_name' => $originalName,
                                'file_type' => $fileExt,
                                's3_filepath' => $filePath,
                                's3_key' => $uniqueS3DocName,
                            ]);
                        /*$document->file_name = $originalName;
                        $document->file_type = $fileExt;
                        $document->s3_filepath = $filePath;
                        $document->s3_key = $uniqueS3DocName;
                        $document->save();*/
                    } else {
                        $path = Storage::disk('s3AllPublicMedia')->put($fileKey, file_get_contents($file));
                        $document = new InvoiceDocument();
                        $document->setConnection($this->connection);
                        $document->invoice_id = $request->invoice_id;
                        $document->file_name = $originalName;
                        $document->file_type = $fileExt;
                        $document->s3_filepath = $filePath;
                        $document->s3_key = $uniqueS3DocName;
                        $document->save();
                    }

                    $resp['status'] = true;
                    $resp['msg'] = 'Invoice has been uploaded successfully!';
                    $resp['data'] = $document;
                } catch (\Exception $e) {
                    $resp['status'] = false;
                    $resp['error_msg'] = $e->getMessage();
                }
                return response()->json($resp);
            }
        }
    }
}
