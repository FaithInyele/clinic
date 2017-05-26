<?php

namespace App\Http\Controllers;

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

    public function pending(){
        $pending = DB::table('tickets')
            ->join('lab_datas', 'tickets.id', '=', 'lab_datas.ticket_id')
            ->select('tickets.*')
            ->where('lab_datas.status', -1)
            ->get();
        if ($pending){
            foreach ($pending as $ticket){
                $ticket->progress = Progress::where('ticket_id', $ticket->id)->latest()->first();
                $ticket->client = Clients::findorFail($ticket->client_id);
                $ticket->lab_datas = LabData::where('ticket_id', $ticket->id)->first();
                if ($ticket->lab_datas){
                    $ticket->tests = Test::where('lab_id', $ticket->lab_datas->id)->get();
                    $ticket->total = Test::where('lab_id', $ticket->lab_datas->id)->sum('amount');
                    if ($ticket->tests){
                        foreach ($ticket->tests as $test){
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
            'ticket_id'=>$request->id,
            'type'=>$request->progress['description'],
            'amount'=>$request->total,
            'payment_method'=>$request->payment_method
        ));
        $payment->save();

        //update lab_datas
        $lab_datas = LabData::findorFail($request->lab_datas['id']);
        $lab_datas->update(['status'=>0]);

        return Response::json($request->lab_datas['id']);
    }
}
