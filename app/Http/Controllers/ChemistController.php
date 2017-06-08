<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChemistResource;
use App\Ticket;
use App\Progress;
use App\Clients;
use App\LabData;
use App\Test;
use App\Prescription;
use App\Medicine;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Payment;

class ChemistController extends Controller
{
    /**
     * get all details about a specified ticket -->relevant data to a chemist.
     *the ticket_id refers to prescription_id..sorry guys.
     * @param $ticket_id
     * @return mixed
     */
    public function currentTicket($ticket_id){
        $data = Prescription::findorFail($ticket_id);
        $data['ticket'] = Ticket::findorFail($data->ticket_id);
        $data['progress'] = Progress::where('ticket_id', $data['ticket']->id)->latest()->first();
        $data['client'] = Clients::findorFail($data['ticket']->client_id);
        $data['medicine'] = Medicine::where('prescription_id', $ticket_id)->get();
        $data['total'] = $total = Medicine::where('prescription_id', $ticket_id)->where('status','issued')->sum('amount');
        foreach ($data['medicine'] as $medicine){
            $medicine['details'] = ChemistResource::findorFail($medicine->chemist_resource_id);
        }
        return Response::json($data);
    }
    public function closePrescription(Request $request){
        //close prescription (doctor's part)
        $prescription = Prescription::findorFail($request->prescription_id);
        DB::table('prescriptions')
            ->where('id', $request->prescription_id)
            ->update(['status'=>2]);

        //make payment
        $payment = new Payment(array(
            'ticket_id'=>$request->ticket_id,
            'type'=>'at Chemist',
            'amount'=>$request->amount,
            'payment_method'=>$request->payment_method
        ));
        $payment->save();

        if ($prescription->type == 0){
            //update progress
            $progress = new Progress(array(
                'ticket_id'=>$request->ticket_id,
                'user_id'=>Auth::user()->id,
                'level'=>5,
                'description'=>'Prescription Given'));
            $progress->save();
        }else{
            //update progress
            $progress = new Progress(array(
                'ticket_id'=>$request->ticket_id,
                'user_id'=>Auth::user()->id,
                'level'=>8,
                'description'=>'Prescription Given to In-patient'));
            $progress->save();
        }

        return Response::json(array('success'=>'success'));
    }
    /**
     * function called by doctor, to submit final
     *
     * @param $prescription_id
     * @return mixed
     */
    public function submitPrescription($prescription_id, Request $request){
        //dd($request->ticket_id);
        //close prescription (doctor's part)
        DB::table('prescriptions')
            ->where('id', $prescription_id)
            ->update(['status'=>1]);

        //dd('huh');
        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$request->ticket_id,
            'user_id'=>Auth::user()->id,
            'level'=>4,
            'description'=>'Client at Chemist'));
        $progress->save();

        return Response::json(array('success'=>'success'));
    }

    public function submitPrescriptionInpatient($prescription_id, Request $request){
        //dd($request->ticket_id);
        //close prescription (doctor's part)
        DB::table('prescriptions')
            ->where('id', $prescription_id)
            ->update(['status'=>1]);

        //dd('huh');
        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$request->ticket_id,
            'user_id'=>Auth::user()->id,
            'level'=>8,
            'description'=>'In-patient at Chemist'));
        $progress->save();

        return Response::json(array('success'=>'success'));
    }

    /**
     * update prescription //bt chemist when issuing medication.
     * @param Request $request
     * @return mixed
     */
    public function updatePrescription(Request $request){
        DB::table('medicines')
            ->where('id', $request->id)
            ->update(['status'=>$request->status, 'alternatative'=>$request->alternatative]);
        $total = Medicine::where('prescription_id', $request->prescription_id)->where('status','issued')->sum('amount');

        return Response::json(array('total'=>$total));
    }

    /**
     * search for a given medication.
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request){
        //dd('huh');
        $results = ChemistResource::where('resource_name', 'LIKE', '%'.$request->q.'%')
            ->orwhere('description', 'LIKE', '%'.$request->q.'%')
            ->get();
        //dd('huh');
        if ($request->prescription_id == 'null'){
            foreach ($results as $result){
                $result['status'] = false;
            }
        }else{
            foreach ($results as $result){
                if ($medicine = Medicine::where('prescription_id', $request->prescription_id)
                    ->where('chemist_resource_id', $result->id)->exists()){
                    $result['status'] = true;
                }else{
                    $result['status'] = false;
                }
            }
        }
        return Response::json($results);
    }
}
