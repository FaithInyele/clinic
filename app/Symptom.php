<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{

    protected $fillable = array('ticket_id', 'description');

}
