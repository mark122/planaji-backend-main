<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    public $connection= '';
    protected $table = "plans";
    
    protected $fillable = [
        'participant_id',
        'plan_contract',
        'status',
        'plan_date_start',
        'plan_date_end',
        'total_funding',
        'capacity_budget',
        'core_budget',
        'capital_budget',
        'capacity_remaining',
        'core_remaining',
        'capital_remaining',
        'total_allocated',
        'total_remaining',
        'total_delivered',
        'total_claimed',
        'total_unclaimed'
    ];

    public function documents()
    {
        return $this->hasMany(PlanDocument::class, 'plan_id', 'id');
    }
}
