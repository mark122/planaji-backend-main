<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDocument extends Model
{
    public $connection= '';
    
    use SoftDeletes;
    protected $table = "invoice_documents";
    protected $fillable = ['invoice_id', 'file_name', 'file_type', 's3_filepath', 's3_key'];

}
