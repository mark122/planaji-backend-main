<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceLinkemails extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'id',
        'invoice_id',    
        'invoice_email_id'
    ];
}
