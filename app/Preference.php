<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $fillable = array('name', 'description', 'status');

    public function fee()
    {
        return $this->hasOne('App\ServiceFee', 'preference_id');
    }
}
