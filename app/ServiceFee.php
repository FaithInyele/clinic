<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceFee extends Model
{
    protected $fillable = array('preference_id', 'amount');
}
