<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportCoordinators extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'planmanager_subscriptions_id','firstname', 'lastname','office', 'mobile', 'email', 'address1', 'address2', 'state', 'postcode', 'participant_ndis'
    ];
}
