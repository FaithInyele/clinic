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
use App\Prescription;
use App\Medicine;
use Illuminate\Support\Facades\DB;

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
            'level'=>0,
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
        $ticket['assigned_by'] = User::findorFail($ticket->issued_by);
        $ticket['assigned_to'] = User::findorFail($ticket->assigned_to);
        $ticket['progress'] = Progress::where('ticket_id', $ticket_id)->latest()->first();
        $ticket['client'] = Clients::findorFail($ticket->client_id);
        $ticket['lab_datas'] = LabData::where('ticket_id', $ticket_id)->first();
        $ticket['symptoms'] = Symptom::where('ticket_id', $ticket_id)->get();
        $ticket['prescription'] = Prescription::where('ticket_id', $ticket_id)->first();
        //get lab technician assigned lab test
        if ($ticket['lab_datas'] != null || !empty($ticket['lab_datas'])){
            $ticket['lab_technician'] = User::findorFail($ticket['lab_datas']->assigned_to);
        }
        //implode symptom as tags
        if ($ticket['symptoms'] != null){
            $tags = array();
            foreach ($ticket['symptoms'] as $symptom){
                $tags[] = $symptom->description;
            }
            $ticket['tags'] = $tags;
        }
        //implode test description as tags
        if ($ticket['lab_datas'] != null){
            $ticket['tests'] = Test::where('lab_id', $ticket['lab_datas']->id)->get();
            $tests = array();
            foreach ($ticket['tests'] as $test){
                $tests[] = $test->description;
            }
            $ticket['ticket_tags'] = $tests;
        }
        //implode prescription as tags
        if ($ticket['prescription'] != null){
            $ticket['med'] = 'huh';
            $ticket['medicine'] = Medicine::where('prescription_id', $ticket['prescription']->id)->get();
            $p_tags = array();
            foreach ($ticket['medicine'] as $medicine){
                $p_tags[] = $medicine->medicine;
            }
            $ticket['medicine_tags'] = $p_tags;
        }
        return Response::json($ticket);
    }

    /**
     * formally (Doc/Nurse) receive a client. and save symptoms described
     *
     * @param Request $request
     * @return mixed
     */
    public function saveSymptoms(Request $request){
        //delete any previous symptoms before a fresh insert, if any
        $toDelete = Symptom::where('ticket_id', $request->ticket_id)->delete();
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
            'level'=>1,
            'description'=>'Client at Doctor'));
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
     * saving client tests* to lab for tests
     *
     * @param Request $request
     */
    public function startLab(Request $request){
        //start Lab Ticket
        if($request->labdatas_id == 'null'){
            $startLab = new LabData(array(
                'ticket_id'=>$request->ticket_id,
                'assigned_to'=>$request->technician,
                'status'=>-1
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

            return Response::json($startLab);
        }else{
            //delete previous tests
            $toDelete = Test::where('lab_id', $request->labdatas_id)->delete();
            //save lab tests
            $test = explode(',', $request->tests);
            foreach ($test as $item){
                $data = new Test(array(
                    'lab_id'=>$request->labdatas_id,
                    'description'=>$item
                ));
                $data->save();
            }
            $startLab = LabData::findorFail($request->labdatas_id);
            return Response::json($startLab);
        }

        //ya ..yaya...too tired to write json response. ill just capture http response code. ...zzzz
    }

    public function sendLab(Request $request){
        //update lab datas table
        DB::table('lab_datas')
            ->where('id', $request->labdatas_id)
            ->update(['status'=>0]);

        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$request->ticket_id,
            'user_id'=>Auth::user()->id,
            'level'=>2,
            'description'=>'Client at Lab'));
        $progress->save();
        $progress = Progress::where('ticket_id', $request->ticket_id)->latest()->first();
        return Response::json($progress);
    }

    public function startChemist(Request $request){
        if ($request->prescription_id == 'none'){
            dd('hoho');
            $prescription = new Prescription(array(
                'ticket_id'=>$request->ticket_id,
                'assigned_to'=>2,
                'status'=>0
            ));
            $prescription->save();
            //save requested medications
            $medication = explode(',', $request->med);
            foreach ($medication as $item){
                $data = new Medicine(array(
                    'prescription_id'=>$prescription->id,
                    'medicine'=>$item,
                    'status'=>'pending'
                ));
                $data->save();
            }
        }else{
            $toDelete = Medicine::where('prescription_id', $request->prescription_id)->delete();
            //save medication
            $medication = explode(',', $request->med);
            foreach ($medication as $item){
                $data = new Medicine(array(
                    'prescription_id'=>$request->prescription_id,
                    'medicine'=>$item,
                    'status'=>'pending'
                ));
                $data->save();
            }
        }
        return Response::json(array('success'=>'success'));

    }
}
