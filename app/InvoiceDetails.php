<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'invoice_id',
        'ndis_pricingguide_id',
        'description',
        'service_start_date',
        'service_end_date',
        'quantity',
        'unit_price',
        'gst_code',
        'amount',
        'hours',
        'claim_type_id',
        'cancellation_reason_id',
        'claim_reference'
    ];
}
