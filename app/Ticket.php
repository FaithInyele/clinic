<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = array('client_id', 'issued_by', 'assigned_to', 'status', 'notes');

}
