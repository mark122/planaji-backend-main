<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'type','no_of_users', 'no_of_participants', 'no_of_service_providers','no_of_support_coordinators','custom_url','custom_email','price'
    ];
}
