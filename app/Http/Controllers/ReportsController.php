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

    public function ticketTrend(Request $request){
        $year = $request->year;

        if($year ==null){
            $year = date('Y');
        }
        //$facilitydata = Facility::findorfail($id);
        $tickets = Ticket::all();
        $jan = 0;$feb=0;$mar=0;$apr=0;$may=0;$jun=0;
        $jul=0;$aug=0;$sep=0;$oct=0;$nov=0;$dec=0;


        $startdate = date('Y-M-d', strtotime($year.'-01-01'));
        $enddate = date('Y-M-d', strtotime($year.'-12-31'));
        //dd($enddate);
        foreach($tickets as $value) {
            $dated = $value->created_at;
            $thedate = date('M', strtotime($dated));
            $theyear = date('Y', strtotime($dated));
            if ($theyear == $year) {
                if ($thedate == 'Jan') {
                    $jan++;
                }
                if ($thedate == 'Feb') {
                    $feb++;
                }
                if ($thedate == 'Mar') {
                    $mar++;
                }
                if ($thedate == 'Apr') {
                    $apr++;
                }
                if ($thedate == 'May') {
                    $may++;
                }
                if ($thedate == 'Jun') {
                    $jun++;
                }
                if ($thedate == 'Jul') {
                    $jul++;
                }
                if ($thedate == 'Aug') {
                    $aug++;
                }
                if ($thedate == 'Sep') {
                    $sep++;
                }
                if ($thedate == 'Oct') {
                    $oct++;
                }
                if ($thedate == 'Nov') {
                    $nov++;
                }
                if ($thedate == 'Dec') {
                    $dec++;
                }
            }

        }
        $stat =array();

        array_push($stat, 'Jan');
        array_push($stat, 'Feb');
        array_push($stat, 'Mar');
        array_push($stat, 'Apr');
        array_push($stat, 'May');
        array_push($stat, 'Jun');
        array_push($stat, 'Jul');
        array_push($stat, 'Aug');
        array_push($stat, 'Sep');
        array_push($stat, 'Oct');
        array_push($stat, 'Nov');
        array_push($stat, 'Dec');

        $values = array();
        array_push($values, $jan);
        array_push($values, $feb);
        array_push($values, $mar);
        array_push($values, $apr);
        array_push($values, $may);
        array_push($values, $jun);
        array_push($values, $jul);
        array_push($values, $aug);
        array_push($values, $sep);
        array_push($values, $oct);
        array_push($values, $nov);
        array_push($values, $dec);


        return Response::json(array('dates'=>$stat, 'values'=>$values));
    }
    public function paymentTrend(Request $request){
        $year = $request->year;

        if($year ==null){
            $year = date('Y');
        }
        //$facilitydata = Facility::findorfail($id);
        $tickets = Payment::all();
        $jan = 0;$feb=0;$mar=0;$apr=0;$may=0;$jun=0;
        $jul=0;$aug=0;$sep=0;$oct=0;$nov=0;$dec=0;


        $startdate = date('Y-M-d', strtotime($year.'-01-01'));
        $enddate = date('Y-M-d', strtotime($year.'-12-31'));
        //dd($enddate);
        foreach($tickets as $value) {
            $dated = $value->created_at;
            $thedate = date('M', strtotime($dated));
            $theyear = date('Y', strtotime($dated));
            if ($theyear == $year) {
                if ($thedate == 'Jan') {
                    $jan = $jan+ $value->amount;
                }
                if ($thedate == 'Feb') {
                    $feb = $feb + $value->amount;
                }
                if ($thedate == 'Mar') {
                    $mar = $mar + $value->amount;
                }
                if ($thedate == 'Apr') {
                    $apr = $apr + $value->amount;
                }
                if ($thedate == 'May') {
                    $may = $may + $value->amount;
                }
                if ($thedate == 'Jun') {
                    $jun = $jun + $value->amount;
                }
                if ($thedate == 'Jul') {
                    $jul = $jul + $value->amount;
                }
                if ($thedate == 'Aug') {
                    $aug = $aug + $value->amount;
                }
                if ($thedate == 'Sep') {
                    $sep = $sep + $value->amount;
                }
                if ($thedate == 'Oct') {
                    $oct = $oct + $value->amount;
                }
                if ($thedate == 'Nov') {
                    $nov = $nov+ $value->amount;
                }
                if ($thedate == 'Dec') {
                    $dec = $dec + $value->amount;
                }
            }

        }
        $stat =array();

        array_push($stat, 'Jan');
        array_push($stat, 'Feb');
        array_push($stat, 'Mar');
        array_push($stat, 'Apr');
        array_push($stat, 'May');
        array_push($stat, 'Jun');
        array_push($stat, 'Jul');
        array_push($stat, 'Aug');
        array_push($stat, 'Sep');
        array_push($stat, 'Oct');
        array_push($stat, 'Nov');
        array_push($stat, 'Dec');

        $values = array();
        array_push($values, $jan);
        array_push($values, $feb);
        array_push($values, $mar);
        array_push($values, $apr);
        array_push($values, $may);
        array_push($values, $jun);
        array_push($values, $jul);
        array_push($values, $aug);
        array_push($values, $sep);
        array_push($values, $oct);
        array_push($values, $nov);
        array_push($values, $dec);


        return Response::json(array('dates'=>$stat, 'values'=>$values));
    }
    public function testTrend(){
        $tests = DB::table('tests')
            ->select('tests.*')
            ->groupBy('tests.lab_resource_id')
            ->count();
        dd($tests);
    }
}
