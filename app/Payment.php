<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = array('ticket_id', 'type', 'amount', 'payment_method');
}
