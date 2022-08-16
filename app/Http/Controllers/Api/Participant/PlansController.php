<?php

namespace App\Http\Controllers\Api\Participant;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\Participant\Controller;

class PlansController extends Controller
{

    private $connection;
    private $plan_manager_subscription_id;

    public function __construct()
    {
        $user = $this->authUser();
        if (empty($user->connection)) {
            return false;
        } else {
            $this->connection = $user->connection;
            $this->plan_manager_subscription_id = $user->planmanager_subscriptions_id;
        }
    }

    public function list(Request $request, $participant_id)
    {
        $user = $this->authUser();
        // if ($request->app_name == "planaji") {
        //     if ($user->original != null) {
        //         if ($user->original['has_error']) {
        //             $response = array(
        //                 'settings' => [
        //                     "status" => 0,
        //                     "message" => $user->original['msg']
        //                 ],
        //                 'data' => []
        //             );
        //             return response()->json($response, 401);
        //         }
        //     }
        //     $list = DB::connection($this->connection)->table('plans')->where('participant_id', '=', $participant_id)->get();

        //     $planlist = DB::connection($this->connection)->table('plans')
        //         ->selectRaw('
        //         plans.id as plan_id, 
        //         plans.plan_contract,
        //         plans.status, 
        //         plans.plan_date_start,
        //         plans.plan_date_end,
        //         plans.total_funding,
        //         plans.total_allocated,
        //         plans.total_remaining,
        //         plans.total_delivered,
        //         plans.total_claimed,
        //         plans.total_unclaimed,
        //         plans.core_budget as core_total_budget, 
        //         plans.core_remaining as core_available,
        //         ROUND((plans.core_budget - plans.core_remaining), 2) as core_spent,
        //         plans.capital_budget as capital_total_budget, 
        //         plans.capital_remaining as capital_available,
        //         ROUND((plans.capital_budget - plans.capital_remaining), 2) as capital_spent,
        //         plans.capacity_budget as capacity_total_budget, 
        //         plans.capacity_remaining as capacity_available,
        //         ROUND((plans.capacity_budget - plans.capacity_remaining), 2) as capacity_spent
        //         ')
        //         ->whereNull('plans.deleted_at')
        //         ->where('plans.participant_id', '=', $participant_id)->get();
        // } else {
            if ($user->original != null) {
                if ($user->original['has_error']) {
                    $response = array(
                        'settings' => [
                            "status" => 0,
                            "message" => $user->original['msg']
                        ],
                        'data' => []
                    );
                    return response()->json($response, 401);
                }
            }
            $list = DB::connection($this->connection)->table('plans')->where('participant_id', '=', $participant_id)->get();

            $planlist = DB::connection($this->connection)->table('plans')
                ->selectRaw('
                plans.id as plan_id, 
                plans.plan_contract,
                plans.status, 
                plans.plan_date_start,
                plans.plan_date_end,
                plans.total_funding,
                plans.total_allocated,
                plans.total_remaining,
                plans.total_delivered,
                plans.total_claimed,
                plans.total_unclaimed,
                plans.core_budget as core_total_budget, 
                plans.core_remaining as core_available,
                ROUND((plans.core_budget - plans.core_remaining), 2) as core_spent,
                plans.capital_budget as capital_total_budget, 
                plans.capital_remaining as capital_available,
                ROUND((plans.capital_budget - plans.capital_remaining), 2) as capital_spent,
                plans.capacity_budget as capacity_total_budget, 
                plans.capacity_remaining as capacity_available,
                ROUND((plans.capacity_budget - plans.capacity_remaining), 2) as capacity_spent
                ')
                ->whereNull('plans.deleted_at')
                ->where('plans.participant_id', '=', $participant_id)->get();
        //}
        if (empty(count($list))) {
            $response = array(
                'settings' => [
                    "status" => 1,
                    "message" => "No data found"
                ],
                'data' => []
            );
        } else {

            $response = array(
                'settings' => [
                    "status" => 1,
                    "message" => "Success"
                ],
                'data' => $planlist
            );
        }

        return response()->json($response);
    }


    public function details(Request $request, $participant_id, $plan_id)
    {
        $user = $this->authUser();
        // if ($request->app_name == "planaji") {
        //     if ($user->original != null) {
        //         if ($user->original['has_error']) {
        //             $response = array(
        //                 'settings' => [
        //                     "status" => 0,
        //                     "message" => $user->original['msg']
        //                 ],
        //                 'data' => []
        //             );
        //             return response()->json($response, 401);
        //         }
        //     }
        //     $list = DB::connection($this->connection)->table('plan_details')
        //         // ->selectRaw('
        //         //     plan_details.id,
        //         //     plan_details.category_budget,
        //         //     plan_details.has_stated_item,
        //         //     plan_details.details,
        //         //     plan_details.support_payment,
        //         //     plan_details.has_quarantine_fund
        //         //     ')
        //         ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
        //         ->whereNull('plan_details.deleted_at')
        //         ->whereNull('plans.deleted_at')
        //         ->where('plans.participant_id', '=', $participant_id)
        //         ->where('plan_details.plan_id', '=', $plan_id)->get();


        //     $corelist = DB::connection($this->connection)->table('plan_details')
        //         ->selectRaw('
        //         plan_details.id,
        //         support_purposes.purpose as support_purpose,
        //         support_categories.support_category,
        //         outcome_domains.outcome_domain as outcome_domain,
        //         plan_details.has_stated_item,
        //         ndis_pricingguides.support_item_number,
        //         ndis_pricingguides.support_item_name,
        //         has_quarantine_fund,
        //         service_providers.firstname as provider_firstname,
        //         service_providers.lastname as provider_lastname, 
        //         plan_details.category_budget as budget,
        //         details,
        //         support_payment,
        //         plans.core_budget as core_total_budget, 
        //         plans.core_remaining as core_available,
        //         ROUND((plans.core_budget - plans.core_remaining), 2) as core_spent
        //         ')
        //         ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
        //         ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
        //         ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
        //         ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
        //         ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
        //         ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
        //         ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
        //         ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
        //         ->whereNull('plan_details.deleted_at')
        //         ->whereNull('plans.deleted_at')
        //         ->where('plans.participant_id', '=', $participant_id)
        //         ->where('plan_supportreference.support_purposes_id', '=', 1)
        //         ->where('plan_details.plan_id', '=', $plan_id)->get();

        //     $capitallist = DB::connection($this->connection)->table('plan_details')
        //         ->selectRaw('
        //     plan_details.id,
        //     support_purposes.purpose as support_purpose,
        //     support_categories.support_category,
        //     outcome_domains.outcome_domain as outcome_domain,
        //     plan_details.has_stated_item,
        //     ndis_pricingguides.support_item_number,
        //     ndis_pricingguides.support_item_name,
        //     has_quarantine_fund,
        //     service_providers.firstname as provider_firstname,
        //     service_providers.lastname as provider_lastname, 
        //     plan_details.category_budget as budget,
        //     details,
        //     support_payment,
        //     plans.capital_budget as capital_total_budget, 
        //     plans.capital_remaining as capital_available,
        //     ROUND((plans.capital_budget - plans.capital_remaining), 2) as capital_spent
        //     ')
        //         ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
        //         ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
        //         ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
        //         ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
        //         ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
        //         ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
        //         ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
        //         ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
        //         ->whereNull('plan_details.deleted_at')
        //         ->whereNull('plans.deleted_at')
        //         ->where('plans.participant_id', '=', $participant_id)
        //         ->where('plan_supportreference.support_purposes_id', '=', 2)
        //         ->where('plan_details.plan_id', '=', $plan_id)->get();

        //     $capacitylist = DB::connection($this->connection)->table('plan_details')
        //         ->selectRaw('
        //     plan_details.id,
        //     support_purposes.purpose as support_purpose,
        //     support_categories.support_category,
        //     outcome_domains.outcome_domain as outcome_domain,
        //     plan_details.has_stated_item,
        //     ndis_pricingguides.support_item_number,
        //     ndis_pricingguides.support_item_name,
        //     has_quarantine_fund,
        //     service_providers.firstname as provider_firstname,
        //     service_providers.lastname as provider_lastname, 
        //     plan_details.category_budget as budget,
        //     details,
        //     support_payment,
        //     plans.capacity_budget as capacity_total_budget, 
        //     plans.capacity_remaining as capacity_available,
        //     ROUND((plans.capacity_budget - plans.capacity_remaining), 2) as capacity_spent
        //     ')
        //         ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
        //         ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
        //         ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
        //         ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
        //         ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
        //         ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
        //         ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
        //         ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
        //         ->whereNull('plan_details.deleted_at')
        //         ->whereNull('plans.deleted_at')
        //         ->where('plans.participant_id', '=', $participant_id)
        //         ->where('plan_supportreference.support_purposes_id', '=', 3)
        //         ->where('plan_details.plan_id', '=', $plan_id)->get();
        // } else {
            if ($user->original != null) {
                if ($user->original['has_error']) {
                    $response = array(
                        'settings' => [
                            "status" => 0,
                            "message" => $user->original['msg']
                        ],
                        'data' => []
                    );
                    return response()->json($response, 401);
                }
            }
            $list = DB::connection($this->connection)->table('plan_details')
                // ->selectRaw('
                //     plan_details.id,
                //     plan_details.category_budget,
                //     plan_details.has_stated_item,
                //     plan_details.details,
                //     plan_details.support_payment,
                //     plan_details.has_quarantine_fund
                //     ')
                ->join('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->whereNull('plan_details.deleted_at')
                ->whereNull('plans.deleted_at')
                ->where('plans.participant_id', '=', $participant_id)
                ->where('plan_details.plan_id', '=', $plan_id)->get();

            $corelist = DB::connection($this->connection)->table('plan_details')
                ->selectRaw('
                plan_details.id,
                support_purposes.purpose as support_purpose,
                support_categories.support_category,
                outcome_domains.outcome_domain as outcome_domain,
                plan_details.has_stated_item,
                ndis_pricingguides.support_item_number,
                ndis_pricingguides.support_item_name,
                has_quarantine_fund,
                service_providers.firstname as provider_firstname,
                service_providers.lastname as provider_lastname, 
                plan_details.category_budget as budget,
                details,
                support_payment,
                plans.core_budget as core_total_budget, 
                plans.core_remaining as core_available,
                ROUND((plans.core_budget - plans.core_remaining), 2) as core_spent
                ')
                ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
                ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
                ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
                ->whereNull('plan_details.deleted_at')
                ->whereNull('plans.deleted_at')
                ->where('plans.participant_id', '=', $participant_id)
                ->where('plan_supportreference.support_purposes_id', '=', 1)
                ->where('plan_details.plan_id', '=', $plan_id)->get();

            $capitallist = DB::connection($this->connection)->table('plan_details')
                ->selectRaw('
            plan_details.id,
            support_purposes.purpose as support_purpose,
            support_categories.support_category,
            outcome_domains.outcome_domain as outcome_domain,
            plan_details.has_stated_item,
            ndis_pricingguides.support_item_number,
            ndis_pricingguides.support_item_name,
            has_quarantine_fund,
            service_providers.firstname as provider_firstname,
            service_providers.lastname as provider_lastname, 
            plan_details.category_budget as budget,
            details,
            support_payment,
            plans.capital_budget as capital_total_budget, 
            plans.capital_remaining as capital_available,
            ROUND((plans.capital_budget - plans.capital_remaining), 2) as capital_spent
            ')
                ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
                ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
                ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
                ->whereNull('plan_details.deleted_at')
                ->whereNull('plans.deleted_at')
                ->where('plans.participant_id', '=', $participant_id)
                ->where('plan_supportreference.support_purposes_id', '=', 2)
                ->where('plan_details.plan_id', '=', $plan_id)->get();

            $capacitylist = DB::connection($this->connection)->table('plan_details')
                ->selectRaw('
            plan_details.id,
            support_purposes.purpose as support_purpose,
            support_categories.support_category,
            outcome_domains.outcome_domain as outcome_domain,
            plan_details.has_stated_item,
            ndis_pricingguides.support_item_number,
            ndis_pricingguides.support_item_name,
            has_quarantine_fund,
            service_providers.firstname as provider_firstname,
            service_providers.lastname as provider_lastname, 
            plan_details.category_budget as budget,
            details,
            support_payment,
            plans.capacity_budget as capacity_total_budget, 
            plans.capacity_remaining as capacity_available,
            ROUND((plans.capacity_budget - plans.capacity_remaining), 2) as capacity_spent
            ')
                ->leftJoin('plans', 'plans.id', '=', 'plan_details.plan_id')
                ->leftJoin('plan_supportreference', 'plan_supportreference.id', '=', 'plan_details.plan_supportreference_id')
                ->leftJoin('support_purposes', 'support_purposes.id', '=', 'plan_supportreference.support_purposes_id')
                ->leftJoin('support_categories', 'support_categories.id', '=', 'plan_supportreference.support_categories_id')
                ->leftJoin('outcome_domains', 'outcome_domains.id', '=', 'plan_supportreference.outcome_domains_id')
                ->leftJoin('plandetails_stateditems', 'plandetails_stateditems.plan_details_id', '=', 'plan_details.id')
                ->leftJoin('ndis_pricingguides', 'ndis_pricingguides.id', '=', 'plandetails_stateditems.ndis_pricingguides_id')
                ->leftJoin('service_providers', 'service_providers.id', '=', 'plan_details.participant_serviceproviders_id')
                ->whereNull('plan_details.deleted_at')
                ->whereNull('plans.deleted_at')
                ->where('plans.participant_id', '=', $participant_id)
                ->where('plan_supportreference.support_purposes_id', '=', 3)
                ->where('plan_details.plan_id', '=', $plan_id)->get();
        //}

        if (empty(count($corelist))) {
            $response = array(
                'settings' => [
                    "status" => 1,
                    "message" => "No data found"
                ],
                'data' => []
            );
        } else {

            $response = array(
                'settings' => [
                    "status" => 1,
                    "message" => "Success"
                ],
                'data' => [
                    'core' => $corelist,
                    'capital' => $capitallist,
                    'capacity' => $capacitylist
                ]
            );
        }

        return response()->json($response);
    }
}
