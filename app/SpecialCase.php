<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialCase extends Model
{
    public $table='special_cases';

    protected $fillable = array('client_id', 'description');

}
