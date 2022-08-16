<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanDetail extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'plan_id',
        'plan_supportreference_id',
        'category_budget',
        'remaining_budget',
        'has_stated_item',
        'is_sent_email',
        'details',
        'support_payment',
        'has_quarantine_fund',
        'participant_serviceproviders_id',
        'participant_supportcoordinators_id',
    ];
}
