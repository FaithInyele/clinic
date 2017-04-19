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

class ChemistController extends Controller
{
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
}
