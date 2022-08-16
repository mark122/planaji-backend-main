<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceProviders extends Model
{
    public $connection= '';

    protected $fillable = [
        'planmanager_subscriptions_id','provider_type_id', 'firstname', 'lastname', 'mobile', 'address1', 'address2', 'state', 'postcode','email', 'abn'
    ];
}
