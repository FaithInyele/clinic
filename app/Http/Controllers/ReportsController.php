<?php

namespace App\Http\Controllers;

use App\InPatient;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function doctor(){
        $user = User::findorFail(Auth::user()->id);
        $tickets = Ticket::where('assigned_to', '=', $user->id)->count();
        $active_tickets = Ticket::where('assigned_to', '=', $user->id)->where('status', 'open');
        $active_count = $active_tickets->count();
        if ($active_tickets->get()){
            $inpatient = 0;
            $outpatient = 0;
            foreach ($active_tickets->get() as $active){
                if (InPatient::where('ticket_id', $active->id)->exists()){
                    $inpatient++;
                }else{
                    $outpatient++;
                }
            }
        }
        dd($outpatient);
    }
}
