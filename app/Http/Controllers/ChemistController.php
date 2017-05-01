<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\Progress;
use App\Clients;
use App\LabData;
use App\Test;
use App\Prescription;
use App\Medicine;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

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
        $ticket['progress'] = Progress::where('ticket_id', $ticket_id)->get();
        $ticket['client'] = Clients::findorFail($ticket->client_id);
        $ticket['lab_data'] = LabData::where('ticket_id', $ticket_id)->first();
        $ticket['tests'] = Test::where('lab_id', $ticket['lab_data']->id)->get();
        $ticket['prescription'] = Prescription::where('ticket_id', $ticket_id)->first();
        $ticket['medicine'] = Medicine::where('prescription_id', $ticket['prescription']->id)->get();

        return Response::json($ticket);
    }

    public function closePrescription($prescription_id){

    }

    /**
     * function called by doctor, to submit final
     *
     * @param $prescription_id
     * @return mixed
     */
    public function submitPrescription($prescription_id){
        DB::table('prescriptions')
            ->where('id', $prescription_id)
            ->update(['status'=>1]);

        return Response::json(array('success'=>'success'));
    }
}
