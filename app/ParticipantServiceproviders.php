<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantServiceproviders extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'participant_id','service_provider_id','planmanager_subscriptions_id','plan_id'
        ];
}
