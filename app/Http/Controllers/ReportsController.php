<?php

namespace App\Http\Controllers;

use App\InPatient;
use App\LabData;
use App\Medicine;
use App\Payment;
use App\Prescription;
use App\Test;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Vinkla\Pusher\Facades\Pusher;

class ReportsController extends Controller
{
    public function doctor(){
        //dd(Carbon::today());
        $user = User::findorFail(Auth::user()->id);
        if ($user->role == 'Receptionist'){
            $tickets = Ticket::where('issued_by', '=', $user->id)->count();
            $today = Ticket::where('issued_by', '=', $user->id)->where('created_at','>=' ,Carbon::today()->toDateTimeString())->count();
            $active_tickets = Ticket::where('issued_by', '=', $user->id)->where('status', 'open');
        }elseif ($user->role == 'Doctor'){
            $tickets = Ticket::where('assigned_to', '=', $user->id)->count();
            $today = Ticket::where('assigned_to', '=', $user->id)->where('created_at','>=' ,Carbon::today()->toDateTimeString())->count();
            $active_tickets = Ticket::where('assigned_to', '=', $user->id)->where('status', 'open');
        }elseif ($user->role == 'Chemist'){
            $tickets = Prescription::where('assigned_to', '=', $user->id)->count();
            $today = Prescription::where('assigned_to', '=', $user->id)->where('created_at','>=' ,Carbon::today()->toDateTimeString())->count();
            $active_tickets = Prescription::where('assigned_to', '=', $user->id)->where('status', 'open');
        }elseif ($user->role == 'Lab Technician'){
            $tickets = LabData::where('assigned_to', '=', $user->id)->count();
            $today = LabData::where('assigned_to', '=', $user->id)->where('created_at','>=' ,Carbon::today()->toDateTimeString())->count();
            $active_tickets = LabData::where('assigned_to', '=', $user->id)->where('status', 'open');
        }
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
            if ($user->role == 'Admin'){

            }else if ($user->role == 'Receptionist'){
                $tickets = Ticket::where('issued_by', $user->id)
                    ->where('created_at', '>=', $current_date->startOfDay()->toDateTimeString())
                    ->where('created_at', '<=', $current_date->endOfDay()->toDateTimeString())
                    ->count();
            }else if ($user->role== 'Doctor'){
                $tickets = Ticket::where('assigned_to', $user->id)
                    ->where('created_at', '>=', $current_date->startOfDay()->toDateTimeString())
                    ->where('created_at', '<=', $current_date->endOfDay()->toDateTimeString())
                    ->count();
            }else if ($user->role== 'Chemist'){
                $tickets = Prescription::where('assigned_to', $user->id)
                    ->where('created_at', '>=', $current_date->startOfDay()->toDateTimeString())
                    ->where('created_at', '<=', $current_date->endOfDay()->toDateTimeString())
                    ->count();
            }else if ($user->role== 'Lab Technician'){
                $tickets = LabData::where('assigned_to', $user->id)
                    ->where('created_at', '>=', $current_date->startOfDay()->toDateTimeString())
                    ->where('created_at', '<=', $current_date->endOfDay()->toDateTimeString())
                    ->count();
            }
            array_push($ticket_count, $tickets);
        }

        return Response::json(array('dates'=>$dates, 'counts'=>$ticket_count));
    }

    public function doctor7(){
        
    }

    public function bridge(){
        Pusher::trigger('my-channel', 'my-event', ['message' => 'huh']);
        dd('haha');
    }
    public function admin(){
        $today = date('y-m-d');
        $carbon_today = Carbon::createFromDate(date('Y', strtotime($today)), date('m', strtotime($today)), date('d', strtotime($today)) );
        $current_user = Auth::user();
        $users = User::count();
        $all_tickets = Ticket::count();
        $active_tickets = Ticket::where('status', 'open')->count();
        $total_payments = Payment::sum('amount');

        //today
        $tickets_created = Ticket::where('created_at', '>=', $carbon_today->startOfDay()->toDateTimeString())
            ->where('created_at', '<=', $carbon_today->endOfDay()->toDateTimeString())
            ->count();
        $admissions_today = InPatient::where('created_at', $carbon_today->startOfDay()->toDateTimeString())
            ->where('created_at', '<=', $carbon_today->endOfDay()->toDateTimeString())
            ->count();
        $discharges_today = DB::table('in_patients')
            ->join('tickets', 'in_patients.ticket_id', 'tickets.id')
            ->where('tickets.updated_at', '>=', $carbon_today->startOfDay()->toDateTimeString())
            ->where('tickets.updated_at', '<=', $carbon_today->endOfDay()->toDateTimeString())
            ->where('tickets.status', 'closed')
            ->count();
        $labdata = LabData::where('created_at', $carbon_today->startOfDay()->toDateTimeString())
                ->where('created_at', '<=', $carbon_today->endOfDay()->toDateTimeString())
                ->count();
        $tests = Test::where('created_at', $carbon_today->startOfDay()->toDateTimeString())
                ->where('created_at', '<=', $carbon_today->endOfDay()->toDateTimeString())
                ->count();

        $prescriptions = Prescription::where('created_at', $carbon_today->startOfDay()->toDateTimeString())
                ->where('created_at', '<=', $carbon_today->endOfDay()->toDateTimeString())
                ->count();
        $medicine = Medicine::where('created_at', $carbon_today->startOfDay()->toDateTimeString())
                ->where('created_at', '<=', $carbon_today->endOfDay()->toDateTimeString())
                ->count();


        $data = array('users'=>$users, 'all_tickets'=>$all_tickets, 'active_tickets'=>$active_tickets, 'total_payments'=>$total_payments,
                'tickets_created'=>$tickets_created, 'admissions_today'=>$admissions_today, 'discharges_today'=>$discharges_today,
                'labdata'=>$labdata, 'tests'=>$tests, 'prescriptions'=>$prescriptions,'medicine'=>$medicine);

        return Response::json($data);

    }
}
