<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanmanagerSubscription extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'subscription_id','plan_manager_id', 'start_date', 'end_date','renewal_date','custom_logo','custom_url','dashboard_side_color','fontheader_color','subscription_no'
    ];

    public static function boot()
    {
       parent::boot();

       static::created(function($model)
       {
    	    $model->subscription_no = 'PLA'. str_pad($model->id,3,'0', STR_PAD_LEFT);
            $model->save();
       });       
    }  
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
