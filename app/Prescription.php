<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{

    protected $fillable = array('ticket_id', 'assigned_to', 'status', 'type');

}
