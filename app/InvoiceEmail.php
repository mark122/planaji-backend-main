<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceEmail extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'id',
        'uuid',
        'plan_manager_id',
        'subject',
        'body',
        'attachment',
        'attachment_url',
        'attachment2',
        'attachment2_url',
        'received_date',
        'from_email',
    ];
}
