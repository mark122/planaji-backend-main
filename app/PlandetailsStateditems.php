<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlandetailsStateditems extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'plan_details_id',
        'ndis_pricingguides_id',
        'stated_items_id',
        'stated_item_budget'
    ];
}