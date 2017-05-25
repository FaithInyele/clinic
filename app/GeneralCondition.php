<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralCondition extends Model
{
    protected $fillable = array('nurse_station_resource_id', 'ticket_id', 'result');
}
