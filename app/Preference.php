<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $fillable = array('name', 'description', 'status');

    /**
     * defining relationship with service fee model
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fee()
    {
        return $this->hasOne('App\ServiceFee', 'preference_id');
    }
}
