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

class ChemistController extends Controller
{
    /**
     * get all details about a specified ticket -->relevant data to a chemist.
     *
     * @param $ticket_id
     * @return mixed
     */
    public function currentTicket($ticket_id){
        //dd('huh');
        $ticket = Ticket::findorFail($ticket_id);
        $ticket['progress'] = Progress::where('ticket_id', $ticket_id)->latest()->first();
        $ticket['client'] = Clients::findorFail($ticket->client_id);
        $ticket['lab_data'] = LabData::where('ticket_id', $ticket_id)->first();
        $ticket['tests'] = Test::where('lab_id', $ticket['lab_data']->id)->get();
        $ticket['prescription'] = Prescription::where('ticket_id', $ticket_id)->first();
        $ticket['medicine'] = Medicine::where('prescription_id', $ticket['prescription']->id)->get();
        foreach ($ticket['medicine'] as $medicine){
            $medicine['details'] = ChemistResource::findorFail($medicine->chemist_resource_id);
        }


        return Response::json($ticket);
    }
    public function closePrescription(Request $request){
        //close prescription (doctor's part)
        DB::table('prescriptions')
            ->where('id', $request->prescription_id)
            ->update(['status'=>2]);

        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$request->ticket_id,
            'user_id'=>Auth::user()->id,
            'level'=>5,
            'description'=>'Prescription Given'));
        $progress->save();

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
    public function updatePrescription(Request $request){
        DB::table('medicines')
            ->where('id', $request->id)
            ->update(['status'=>$request->status, 'alternatative'=>$request->alternatative]);
    }
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
