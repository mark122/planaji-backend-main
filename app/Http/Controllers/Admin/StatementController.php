<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Participants;
use DB;

class StatementController extends Controller
{
    private $connection = NULL;
    private $Participants = NULL;
    public function __construct()
    {
        $this->Participants = new Participants;
        $this->Participants->connection = $this->connection;

        $this->middleware(function ($request, $next) {
            $this->connection=auth()->user()['connection']; // returns user
        return $next($request);
        });
    }

    public function index(Request $request)
    {
        return view('admin.statementpdf');
    }
    
    public function generatePDF(Request $request)
    {
        // dd($request->get('start_date'));

        $profile = DB::connection($this->connection)->table('participants')->where('id','=',$request->get('participant_id'))->get()->first();


        $planmanager= DB::connection($this->connection)->select('select planmanager_subscriptions.id, planmanager_subscriptions.custom_logo,
        plan_managers.primary_contact_email,plan_managers.primary_contact_number,plan_managers.name from planmanager_subscriptions
        JOIN plan_managers ON planmanager_subscriptions.plan_manager_id = plan_managers.id
        WHERE planmanager_subscriptions.id=?',[auth()->user()->plan_manager_subscription_id]);

        $invoices = DB::connection($this->connection)->table('invoices')
                ->selectRaw(
                    '
                invoices.id,
                participants.ndis_number,
                invoices.invoice_number,
                invoices.invoice_date,
                invoices.due_date,
                invoices.serviceprovider_id,
                invoices.reference_number,
                invoices.service_provider_ABN,
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
                ->where('invoices.participant_id', '=', $request->get('participant_id'))
                // ->where('invoices.invoice_date', '>=', $request->get('start_date'))
                // ->where('invoices.invoice_date', '<=', $request->get('end_date'))
                ->orderBy('invoices.id', 'desc')
                ->groupBy(
                    'invoices.id',
                    'participants.ndis_number',
                    'invoices.invoice_number',
                    'invoices.invoice_date',
                    'invoices.due_date',
                    'invoices.serviceprovider_id',
                    'invoices.reference_number',
                    'invoices.service_provider_ABN',
                    'invoices.service_provider_acc_number',
                    'invoices.status',
                    'service_providers.firstname',
                    'service_providers.lastname',
                    'invoices.remarks',
                    'participants.firstname',
                    'participants.lastname',
                    'invoice_status.description'
                )->get();

        $invoice_details = [];
            foreach($invoices as $invoice){
                    $invoice_details[] = DB::connection($this->connection)->table('invoice_details')
                    ->selectRaw(
                        "
                    invoice_details.id,
                    support_purposes.purpose,
                    invoice_details.amount,
                    invoices.invoice_number,
                    ndis_pricingguides.support_item_number,
                    ndis_pricingguides.support_item_name,
                    invoices.invoice_date,
                    gst.code as gstcode,
                    gst.description as gstdesc,
                    claim_type.code as claimtypecode,
                    claim_type.description as claimtypedesc,
                    cancellation_reason.code as cancelcode,
                    cancellation_reason.description as canceldesc,
                    invoice_details.id,
                    service_providers.firstname as service_provider_first_name,
                    service_providers.lastname as service_provider_last_name
                    "
                    )
                    ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                    ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                    ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                    ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->leftJoin('gst', 'gst.id', '=', 'invoice_details.gst_code')
                    ->leftJoin('claim_type', 'claim_type.id', '=', 'invoice_details.claim_type_id')
                    ->leftJoin('cancellation_reason', 'cancellation_reason.id', '=', 'invoice_details.cancellation_reason_id')
                    ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
                    ->where('invoice_details.invoice_id', '=', $invoice->id)
                    ->groupBy(
                    'invoice_details.id',
                    'support_purposes.purpose',
                    'invoice_details.amount',
                    'invoices.invoice_number',
                    'ndis_pricingguides.support_item_number',
                    'ndis_pricingguides.support_item_name',
                    'invoices.invoice_date',
                    'gst.code',
                    'gst.description',
                    'claim_type.code',
                    'claim_type.description',
                    'cancellation_reason.code',
                    'cancellation_reason.description',
                    'invoice_details.id',
                    'service_providers.firstname',
                    'service_providers.lastname'
                    )
                    ->get();
            }

                $core_used_budget = 0;
                $capital_used_budget = 0;
                $capacity_used_budget = 0;
                $prev_core_used_budget = 0;
                $prev_capital_used_budget = 0;
                $prev_capacity_used_budget = 0;
                foreach($invoice_details as $invoices){
                    foreach($invoices as $invoice){
                        if($invoice->invoice_date >= $request->get('start_date') && $invoice->invoice_date <= $request->get('end_date')){
                            if($invoice->purpose == 'Core Supports'){
                                $core_used_budget+= $invoice->amount;
                            }
                            elseif($invoice->purpose == 'Capital Supports'){
                                $capital_used_budget+= $invoice->amount;
                            }
                            elseif($invoice->purpose == 'Capacity Building'){
                                $capacity_used_budget+= $invoice->amount;
                            }
                        }
                        elseif($invoice->invoice_date < $request->get('start_date')){
                            if($invoice->purpose == 'Core Supports'){
                                $prev_core_used_budget+= $invoice->amount;
                            }
                            elseif($invoice->purpose == 'Capital Supports'){
                                $prev_capital_used_budget+= $invoice->amount;
                            }
                            elseif($invoice->purpose == 'Capacity Building'){
                                $prev_capacity_used_budget+= $invoice->amount;
                            }
                        }   
                    }
                }
 
            $core_support_total_budget = DB::connection($this->connection)->table('plan_details')
                ->join('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->join('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->select(DB::connection($this->connection)->raw("SUM(plan_details.category_budget) as total_budget"))
                ->where('plans.participant_id', '=', $request->get('participant_id'))
                ->where('plan_details.plan_id', '=', $request->get('plan_id'))
                ->where('support_purposes.id', '=', 1)->get();
    
    
            $capital_total_budget = DB::connection($this->connection)->table('plan_details')
                ->join('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->join('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->select(DB::connection($this->connection)->raw("SUM(plan_details.category_budget) as total_budget"))
                ->where('plans.participant_id', '=', $request->get('participant_id'))
                ->where('plan_details.plan_id', '=', $request->get('plan_id'))
                ->where('support_purposes.id', '=', 2)->get();
    
            $capacity_building_total_budget = DB::connection($this->connection)->table('plan_details')
                ->join('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->join('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->select(DB::connection($this->connection)->raw("SUM(plan_details.category_budget) as total_budget"))
                ->where('plans.participant_id', '=', $request->get('participant_id'))
                ->where('plan_details.plan_id', '=', $request->get('plan_id'))
                ->where('support_purposes.id', '=', 3)->get();
    
    
            $core_spent = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::connection($this->connection)->raw("SUM(invoice_details.amount) as amount"))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                ->where('plan_supportreference.support_purposes_id', '=', 1)
                ->where('invoices.participant_id', '=', $request->get('participant_id'))
                ->get();
    
            $capital_spent = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::connection($this->connection)->raw("SUM(invoice_details.amount) as amount"))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                ->where('plan_supportreference.support_purposes_id', '=', 2)
                ->where('invoices.participant_id', '=', $request->get('participant_id'))
                ->get();
    
            $capacity_spent = DB::connection($this->connection)->table('invoice_details')
                ->select(DB::connection($this->connection)->raw("SUM(invoice_details.amount) as amount"))
                ->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'invoice_details.ndis_pricingguide_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.support_categories_id', '=', 'ndis_pricingguides.support_category_number')
                ->where('plan_supportreference.support_purposes_id', '=', 3)
                ->where('invoices.participant_id', '=', $request->get('participant_id'))
                ->get();

                $core_remaining = (float) $core_support_total_budget[0]->total_budget - (float) $core_spent[0]->amount;
                $capital_remaining = (float) $capital_total_budget[0]->total_budget - (float) $capital_spent[0]->amount;
                $capacity_remaining = (float) $capacity_building_total_budget[0]->total_budget - (float) $capacity_spent[0]->amount;

                $core_opening = (float) $core_support_total_budget[0]->total_budget - (float)$prev_core_used_budget;
                $capital_opening = (float) $capital_total_budget[0]->total_budget - (float) $prev_capital_used_budget;
                $capacity_opening = (float) $capacity_building_total_budget[0]->total_budget - (float) $prev_capacity_used_budget;

                if($core_support_total_budget[0]->total_budget){
                $core_temp = $core_used_budget + intval($core_support_total_budget[0]->total_budget) - intval($core_opening);
                $core_used = round($core_temp/intval($core_support_total_budget[0]->total_budget)*100, 2);
                }
                else{
                    $core_used = 0;
                }

                
                if($capital_total_budget[0]->total_budget){
                $capital_temp = $capital_used_budget + intval($capital_total_budget[0]->total_budget) - intval($capital_opening);
                $capital_used = round($capital_temp/intval($capital_total_budget[0]->total_budget)*100, 2);
            }
            else{
                $capital_used = 0;
            }
            
            if($capacity_building_total_budget[0]->total_budget){
                $capacity_temp = $capacity_used_budget + intval($capacity_building_total_budget[0]->total_budget) - intval($capacity_opening);
                $capacity_used = round($capacity_temp/intval($capacity_building_total_budget[0]->total_budget)*100, 2);
            }
            else{
                $capacity_used = 0;
            }
        $data = ['title' => 'Welcome to Pakainfo.com',
        'name' => $profile->firstname.' '.$profile->lastname,
        'ndis_number' => $profile->ndis_number,
        'start_date' => $request->get('start_date'),
        'end_date' => $request->get('end_date'),
        'ndis_start_date' => $profile->ndis_plan_start_date,
        'ndis_end_date' => $profile->ndis_plan_end_date,
        'plan_manager_name' => $planmanager[0]->name,
        'plan_manager_email' => $planmanager[0]->primary_contact_email,
        'plan_manager_number' => $planmanager[0]->primary_contact_number,
        'core_used_budget' => $core_used_budget,
        'capital_used_budget' => $capital_used_budget,
        'capacity_used_budget' => $capacity_used_budget,
        'core_opening' => $core_opening,
        'capital_opening' => $capital_opening,
        'capacity_opening' => $capacity_opening,
        'core_used' => $core_used,
        'capital_used' => $capital_used,
        'capacity_used' => $capacity_used
        ];
        $pdf = PDF::loadView('admin/statementpdf', compact(
            'data',
            'planmanager',
            'core_support_total_budget',
            'capital_total_budget',
            'capacity_building_total_budget',
            'invoices',
            'invoice_details'));

        // return view('admin.statementpdf', compact(
        //     'data',
        //     'planmanager',
        //     'core_support_total_budget',
        //     'capital_total_budget',
        //     'capacity_building_total_budget',
        //     'invoices',
        //     'invoice_details'));
        return $pdf->stream('NDIS Statement ('. $data['start_date'].' - '.$data['end_date'].').pdf');
    }
}
