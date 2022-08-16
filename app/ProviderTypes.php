<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderTypes extends Model
{
    public $connection= '';
    
    protected $fillable = [
        'typename'
    ];
    protected $table = 'provider_types';
}
