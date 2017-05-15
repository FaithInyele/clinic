<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{

    protected $fillable = array('ticket_id', 'user_id', 'level','description');

}
