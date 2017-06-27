<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ticket;
use App\Clients;
use App\Test;
use App\LabData;
use App\LabResource;
use Illuminate\Support\Facades\Response;
use App\Progress;
use App\Payment;

class PaymentController extends Controller
{
    public function index(){
        $title = 'iHospital | Ticket Payments';
        $rightbar = 'ticket';

        return view('ticket.payment.index', compact('title', 'rightbar'));
    }

    public function report(){
        $title = 'iHospital | Ticket Payments';
        $rightbar = 'payment';
        $payments = Payment::all();
        foreach ($payments as $payment){
            $payment['ticket'] = Ticket::findorFail($payment->ticket_id);
            $payment['client'] = Clients::findorFail($payment['ticket']->client_id);
        }

        return view('ticket.payment.report', compact('title', 'rightbar', 'payments'));
    }

    public function pending(){
        $pending = LabData::where('status', -1)->get();
        if ($pending){
            foreach ($pending as $item){
                $item->ticket = Ticket::findorFail($item->ticket_id);
                $item->progress = Progress::where('ticket_id', $item->ticket->id)->latest()->first();
                $item->client = Clients::findorFail($item->ticket->client_id);
                $item->doctor = User::findorFail($item->ticket->assigned_to);
                $item->lab_datas = LabData::where('ticket_id', $item->ticket->id)->first();
                if ($item->lab_datas){
                    $item->tests = Test::where('lab_id', $item->id)->get();
                    $item->total = Test::where('lab_id', $item->id)->sum('amount');
                    if ($item->tests){
                        foreach ($item->tests as $test){
                            $test->details = LabResource::findorFail($test->lab_resource_id);
                        }
                    }
                }
            }
        }

        return Response::json($pending);
    }

    public function payLab(Request $request){
        //make payment
        $payment = new Payment(array(
            'ticket_id'=>$request->ticket['id'],
            'type'=>$request->progress['description'],
            'amount'=>$request->total,
            'payment_method'=>$request->payment_method
        ));
        $payment->save();

        //update lab_datas
        $lab_datas = LabData::findorFail($request->id);
        $lab_datas->update(['status'=>0]);

        return Response::json($request->lab_datas['id']);
    }

    public function reportIndividual($Payment_id){

    }
}
