<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanDocument extends Model
{
    public $connection= '';
    
    use SoftDeletes;
    protected $table = "plan_documents";
    protected $fillable = ['plan_id', 'file_name', 'file_type', 's3_filepath', 's3_key'];

    public function plan()
    {
        return $this->belongsTo(Plans::class);
    }

}
