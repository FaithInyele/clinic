<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralConditionResource extends Model
{
    protected $fillable = array('resource_name', 'resource_description', 'status');
}
