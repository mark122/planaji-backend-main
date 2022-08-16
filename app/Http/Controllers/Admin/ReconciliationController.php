<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\User;
use App\InvoiceDetails;



class ReconciliationController extends Controller
{

    private $connection = NULL;
    private $InvoiceDetails = NULL;


    public function __construct()
    {
        $this->InvoiceDetails = new InvoiceDetails;
        $this->User = new User;

        $this->InvoiceDetails->connection = $this->connection;

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
        return view('admin.reconciliation');
    }

    public function loadrecords(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection($this->connection)->table('invoice_details')
                ->selectRaw(
                    '
                    invoices.invoice_number,
                    ndis_pricingguides.support_item_number as item,
                    invoice_details.description,
                    invoice_details.service_start_date,
                    invoice_details.service_end_date,
                    invoice_details.quantity,
                    invoice_details.unit_price,
                    gst.code as gstcode,
                    invoice_details.amount,
                    CASE WHEN invoice_details.payment_request_status IS NULL THEN "N/A" ELSE invoice_details.payment_request_status END as payment_request_status
                    '
                )
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                ->where('invoices.planmanager_subscriptions_id', auth()->user()->plan_manager_subscription_id)
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        $user_id = auth()->user()->id;

        $user_data = DB::connection($this->connection)->table('users')
            ->where('users.id', $user_id)
            ->get()->first();

        $invoice_status = DB::connection($this->connection)->table('invoice_status')->whereIn('id', [2, 4, 5, 6, 7])->get();

        return view('admin.reconciliation', compact('user_data', 'invoice_status'));
    }

    public function upload(Request $request)
    {
        if ($request->ajax()) {
            $response = array();
            $get_affected_data = array();
            $GetClaimReference = array();
            $counter = 0;
            $Reconciliation = $request->reconciliations;
            foreach ($Reconciliation as $key => $item) {
                array_push($GetClaimReference, $item['ClaimReference']);
            }


            $data = DB::connection($this->connection)
                ->table('invoice_details')
                ->whereIn('claim_reference', $GetClaimReference)->get();

            if (!empty(count($data) > 0)) {
                foreach ($Reconciliation as $key => $item) {
                    foreach ($data as $key1 => $item1) {
                        if ($item1->claim_reference == $item['ClaimReference']) {

                            DB::connection($this->connection)
                                ->table('invoice_details')
                                ->where('id', $item1->id)
                                ->update([
                                    'capped_price' => $item['CappedPrice'],
                                    'payment_request_status' => $item['PaymentRequestStatus'],
                                    'error_message' => $item['ErrorMessage'],
                                ]);

                            $get_affected_data[$counter] = [
                                'ClaimReference' => $item['ClaimReference'],
                                'capped_price' => $item['CappedPrice'],
                                'payment_request_status' => $item['PaymentRequestStatus'],
                                'error_message' => $item['ErrorMessage']
                            ];
                            $counter++;
                        }
                    }
                }
                $response = [
                    'success' => true,
                    'msg' => 'Successfully uploaded',
                    'get_affected_data' => $get_affected_data
                ];
            } else {
                $response = [
                    'success' => false,
                    'msg' => 'No affected row'
                ];
            }
            return response()->json($response);
        }
    }


    public function hidesuccessfulinvoices(Request $request)
    {

        if ($request->ajax()) {
            $this->User->connection = $this->connection;

            $user = $this->User->updateOrCreate(
                ['id' => auth()->user()->id],
                [
                    'hide_successful_invoices' => filter_var($request->hidesuccessfulinvoices, FILTER_VALIDATE_BOOLEAN),
                ]
            );

            return response()->json(['success' => true, 'msg' => 'Successfully updated']);
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
            invoice_details.claim_reference as reference_number,
            invoice_details.capped_price,
            invoice_details.payment_request_status,
            invoice_details.error_message,
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
                ->where('invoice_details.payment_request_status', 'ERROR')
                ->whereNull('invoices.deleted_at')
                ->whereNull('invoice_details.deleted_at')
                ->get();
            return response()->json($data);
        }
    }
}
