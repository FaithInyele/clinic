<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = array('lab_id', 'lab_resource_id', 'result', 'amount');
}
