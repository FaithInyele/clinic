<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    public $table='clients';


    protected $fillable = array('first_name', 'other_names', 'gender', 'yob','weight','length',
        'county', 'sub-county','town','village',
        'mothers_names', 'mothers_id_no','mothers_telephone_no','fathers_names','fathers_id_no',
        'fathers_telephone_no','address');
}
