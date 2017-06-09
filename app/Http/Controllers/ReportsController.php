<?php

namespace App\Http\Controllers;

use App\InPatient;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ReportsController extends Controller
{
    public function doctor(){
        //dd(Carbon::today());
        $user = User::findorFail(Auth::user()->id);
        $tickets = Ticket::where('assigned_to', '=', $user->id)->count();
        $today = Ticket::where('assigned_to', '=', $user->id)->where('created_at','>=' ,Carbon::today()->toDateTimeString())->count();
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
        $report = array('total_tickets'=>$tickets,
            'created_today'=>$today,
            'active_tickets'=> $active_count,
            'active_outpatient'=>$outpatient,
            'active_inpatient'=>$inpatient);

        return Response::json($report);
    }
}
