<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'id',
        'planmanager_subscriptions_id',
        'participant_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'reference_number',
        'serviceprovider_id',
        'service_provider_ABN',
        'service_provider_acc_number',
        'status',
        'remarks'
    ];
}
