<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consult extends Model
{
    protected $fillable = array('ticket_id', 'consultant_id');
}
