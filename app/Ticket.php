<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = array('client_id', 'issued_by', 'assigned_to', 'status', 'notes');

    /**
     * relationship with lab_datas table model
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lab_datas(){
        return $this->hasMany('App\LabData', 'ticket_id');
    }

    /**
     * relationshi[ with doctor in user table Model
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function doctor(){
        return $this->hasOne('App\User', 'assigned_to');
    }

    public function client(){
        return $this->hasOne('App\Client', 'client_id');
    }
}
