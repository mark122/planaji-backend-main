<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Participants extends Authenticatable implements JWTSubject
{
    public $connection= '';
    use Notifiable;
    protected $fillable = [
        'id',
        'name',
        'planmanager_subscriptions_id',
        'firstname',
        'lastname',
        'ndis_number',
        'aboutme',
        'address1',
        'address2',
        'state',
        'postcode',
        'homenumber',
        'phonenumber',
        'dateofbirth',
        'email',
        'password',
        'remember_token',
        'generated_password',
        'changed_password',
        'password_token',
        'ndis_plan_start_date',
        'ndis_plan_end_date',
        'ndis_plan_review_date',
        'short_term_goals',
        'long_term_goals',
        'status',
        'app_access_enabled'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
