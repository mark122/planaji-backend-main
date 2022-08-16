<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantSupportcoordinators extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'participant_id', 'support_coordinator_id','planmanager_subscriptions_id','plan_id'
    ];
}


