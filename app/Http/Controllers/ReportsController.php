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

    public function ticketHistory(){
        $user = Auth::user();
        $dates = array();
        array_push($dates, date('Y-M-d'));
        array_push($dates, date('Y-M-d', strtotime('-1 days')));
        array_push($dates, date('Y-M-d', strtotime('-2 days')));
        array_push($dates, date('Y-M-d', strtotime('-3 days')));
        array_push($dates, date('Y-M-d', strtotime('-4 days')));
        array_push($dates, date('Y-M-d', strtotime('-5 days')));
        array_push($dates, date('Y-M-d', strtotime('-6 days')));
        $ticket_count = array();
        foreach ($dates as $data){
            $year= date('Y', strtotime($data));
            $month= date('m', strtotime($data));
            $day= date('d', strtotime($data));
            $current_date = Carbon::createFromDate($year, $month, $day, null);
            $tickets = Ticket::where('assigned_to', $user->id)
                ->where('created_at', '>=', $current_date->startOfDay()->toDateTimeString())
                ->where('created_at', '<=', $current_date->endOfDay()->toDateTimeString())
                ->count();
            array_push($ticket_count, $tickets);

        }

        return Response::json(array('dates'=>$dates, 'counts'=>$ticket_count));
    }

    public function doctor7(){
        
    }
}
