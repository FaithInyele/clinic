<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;
use App\Clients;
use App\User;
use App\Progress;
use App\Symptom;
use App\LabData;
use App\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class TicketController extends Controller
{

    /**
     * display form where a user searches for client, to start a ticket for.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function add_form(){
        $title = 'iHospital | Create New Ticket';
        $rightbar = 'ticket';

        return view('ticket.add', compact('rightbar', 'title'));
    }

    /**
     * api method used by vue js to instantly search for a client.
     *
     * @param Request $request
     * @return mixed
     */
    public function searchClient(Request $request){
        $search_term = $request->term;

        $results = Clients::where('first_name', 'LIKE', '%'.$search_term.'%')
            ->orwhere('other_names', 'LIKE', '%'.$search_term.'%')
            ->orwhere('id_number', 'LIKE', '%'.$search_term.'%')
            ->get();

        return Response::json($results);
    }

    /**
     * display form where a client is assigned a given doctor/nurse
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function start_form(Request $request){
        $title = 'iHospital | Assign Ticket';
        $rightbar = 'ticket';

        $data = $request->all();
        $available_doctors = User::where('role', 'Nurse/Doctor')->get();

        return view('ticket.start', compact('rightbar', 'title', 'data', 'available_doctors'));
    }

    /**
     * save a ticket to db
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function start_store(Request $request){
        //issued by
        $request['issued_by'] = Auth::user()->id;
        //status -->default is open
        $request['status'] = 'open';

        //save ticket to db
        $ticket = new Ticket($request->all());
        $ticket->save();

        //save progress
        $progress = new Progress(array(
            'ticket_id'=>$ticket->id,
            'user_id'=>$ticket->issued_by,
            'description'=>'Ticket Created'));
        $progress->save();

        //return success message to page
        return redirect()->action('TicketController@add_form')
            ->with('status', 'Ticket Successfully created');

    }

    /**
     * view my current tickets page. and be able to manipulate them.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myTickets(){
        $title = 'iHospital | My Tickets';
        $rightbar = 'ticket';

        return view('ticket.my-tickets.index2', compact('title', 'rightbar'));
    }

    /**
     * get all my active tickets to display in myTickets()
     *
     * @return mixed
     */
    public function allActive(){
        //get all tickets for current user
        $ticket = Ticket::where('status', 'open')->get();
        $active = array();
        //add all relevant data to each ticket
        foreach ($ticket as $item){
            $item['client'] = Clients::findorFail($item->client_id);
            $item['progress'] = Progress::where('ticket_id', $item->id)->first();
            array_push($active, $item);
        }

        return Response::json($active);
    }

    /**
     * retrieve all details associated with a particular ticket in allActive()
     *
     * @param $ticket_id
     * @return mixed
     */
    public function selectedTicket($ticket_id){
        $ticket = Ticket::findorFail($ticket_id);
        $ticket['progress'] = Progress::where('ticket_id', $ticket_id)->get();
        $ticket['client'] = Clients::findorFail($ticket->client_id);
        $ticket['lab_datas'] = LabData::where('ticket_id', $ticket_id)->first();
        $ticket['symptoms'] = Symptom::where('ticket_id', $ticket_id)->get();
        $ticket['tests'] = Test::where('lab_id', $ticket['lab_datas']->id)->get();

        return Response::json($ticket);
    }

    /**
     * formally (Doc/Nurse) receive a client. and save symptoms described
     *
     * @param Request $request
     * @return mixed
     */
    public function saveSymptoms(Request $request){
        //save symptoms
        $symptoms = explode(',', $request->symptoms);
        foreach ($symptoms as $symptom){
            $data = new Symptom(array('ticket_id'=>$request->ticket_id,'description'=>$symptom));
            $data->save();
        }

        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$request->ticket_id,
            'user_id'=>Auth::user()->id,
            'description'=>'Client at Doctor\'s'));
        $progress->save();

        return Response::json(array('status'=>'success'));

    }

    /**
     * get all Lab technicians who are Active at the moment-->so they can be assigned tests.
     *
     * @return mixed
     */
    public function activeLabTechnicians(){
        $technitians = User::where('role', 'Lab Technician')->get();

        return Response::json($technitians);

    }

    /**
     * get all Doctors/Nurses who are Active at the moment-->so they can be assigned clients
     *
     * @return mixed
     */
    public function activeDoc(){
        $docs = User::where('role', 'Nurse/Doctor')->get();

        return Response::json($docs);

    }

    /**
     * sending client to lab for tests
     *
     * @param Request $request
     */
    public function startLab(Request $request){
        //dd($request->all());
        //start Lab Ticket
        $startLab = new LabData(array(
            'ticket_id'=>$request->ticket_id,
            'assigned_to'=>$request->technician,
            'status'=>0
        ));
        $startLab->save();

        //save lab tests
        $test = explode(',', $request->tests);
        foreach ($test as $item){
            $data = new Test(array(
                'lab_id'=>$startLab->id,
                'description'=>$item
            ));
            $data->save();
        }

        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$request->ticket_id,
            'user_id'=>Auth::user()->id,
            'description'=>'Client at Lab'));
        $progress->save();

        //ya ..yaya...too tired to write json response. ill just capture http response code. ...zzzz
    }
}
