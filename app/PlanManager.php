<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanManager extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'name','website', 'primary_contact_name', 'primary_contact_email','primary_contact_number','registration_number', 'qbname'
        ];
}
