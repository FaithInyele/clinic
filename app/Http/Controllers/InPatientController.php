<?php

namespace App\Http\Controllers;

use App\InPatient;
use Illuminate\Http\Request;
use App\Progress;
use Illuminate\Support\Facades\Auth;

class InPatientController extends Controller
{
    public function admit($ticket_id){
        $admit = new InPatient(array(
            'ticket_id'=>$ticket_id
        ));
        $admit->save();

        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$ticket_id,
            'user_id'=>Auth::user()->id,
            'level'=>6,
            'description'=>'Client admitted'));
        $progress->save();
    }
}
