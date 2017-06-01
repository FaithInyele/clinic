<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = array('consultant_id', 'from', 'message', 'read_status', 'message_to');
}
