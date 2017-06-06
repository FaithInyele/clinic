<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InPatient extends Model
{
    protected $fillable = array('ticket_id', 'assigned_to');
}
