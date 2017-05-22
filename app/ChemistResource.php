<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChemistResource extends Model
{
    protected $fillable = array('resource_name', 'description', 'unit_price');
}
