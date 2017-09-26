<?php

namespace App\Http\Controllers;

use App\InPatient;
use App\SpecialCase;
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
use App\LabResource;
use App\ChemistResource;
use App\GeneralConditionResource;
use App\GeneralCondition;

class TicketController extends Controller
{

    /**
     * return view for all tickets -->by admin
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $title = 'iHospital | Create New Ticket';
        $rightbar = 'ticket';
        $tickets = Ticket::all();
        foreach ($tickets as $ticket){
            $ticket['doctor'] = User::findorFail($ticket->assigned_to);
            $ticket['client'] = Clients::findorFail($ticket->client_id);
            $ticket['reception'] = User::findorFail($ticket->issued_by);
        }

        return view('ticket.index', compact('tickets', 'title', 'rightbar'));
    }

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
        $available_doctors = User::where('role', 'Doctor')->get();
        $nurse_station_resources = GeneralConditionResource::where('status', 1)->get();
        return view('ticket.start', compact('rightbar', 'title', 'data', 'available_doctors', 'nurse_station_resources'));
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

        //add general checkup results
        foreach ($request->resource as $key =>$resource){
            //dd();
            $check_ups = new GeneralCondition(array(
                'nurse_station_resource_id'=>preg_replace('/\D/', '', $key),
                'ticket_id'=>$ticket->id,
                'result'=>$resource
            ));
            $check_ups->save();
        }


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
     * get all my active tickets to display in myTickets() //for given doc
     *
     * @return mixed
     */
    public function allActive(){
        //get all tickets for current user
        $ticket = Ticket::where('status', 'open')->where('assigned_to', Auth::user()->id)->get();
        $active = array();
        //add all relevant data to each ticket
        foreach ($ticket as $item){
            $item['client'] = Clients::findorFail($item->client_id);
            $item['progress'] = Progress::where('ticket_id', $item->id)->latest()->first();
            $item['inPatient'] = InPatient::where('ticket_id', $item->id)->first();
            if ($item['inPatient'] == null){
                if ($item['progress']->level == 0 || $item['progress']->level == 1 || $item['progress']->level == 3 || $item['progress']->level == 5){
                    array_push($active, $item);
                }
            }
        }

        usort($active, function ($a, $b){
           return $a['progress']['updated_at'] <=> $b['progress']['updated_at'];
        });
        return Response::json($active);
    }

    /**
     * method that gets all clients who were admitted as inpatients //by the current doctor
     * @return mixed
     */
    public function inPatient(){
        //get all tickets for current user
        $ticket = Ticket::where('status', 'open')->where('assigned_to', Auth::user()->id)->get();
        $active = array();
        //add all relevant data to each ticket
        foreach ($ticket as $item){
            $item['client'] = Clients::findorFail($item->client_id);
            $item['progress'] = Progress::where('ticket_id', $item->id)->latest()->first();
            $item['inPatient'] = InPatient::where('ticket_id', $item->id)->first();
            if ($item['inPatient'] != null){
                array_push($active, $item);
            }
        }

        usort($active, function ($a, $b){
            return $a['progress']['updated_at'] <=> $b['progress']['updated_at'];
        });
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
        $ticket['pre_examination'] = GeneralCondition::where('ticket_id', $ticket_id)->get();
        $ticket['special_case'] = SpecialCase::where('client_id', $ticket['client']->id)->first();
        $ticket['inPatient'] = InPatient::where('ticket_id', $ticket_id)->first();
        if ($ticket['pre_examination']){
            foreach ($ticket['pre_examination'] as $examination){
                $examination['details'] = GeneralConditionResource::findorFail($examination->nurse_station_resource_id);
            }
        }
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
            $test_id = array();
            //dd($ticket['tests']);
            foreach ($ticket['tests'] as $test){
                //dd($test->lab_resource_id);
                $test_details = LabResource::findorFail($test->lab_resource_id);
                $test['details'] = $test_details;
                $tests[] = $test_details->resource_name;
                $test_id[] = $test_details->id;
            }
            $ticket['ticket_tags'] = $tests;
            $ticket['test_tagsId'] = $test_id;
        }
        //implode prescription as tags
        if ($ticket['prescription'] != null){
            $ticket['med'] = 'huh';
            $ticket['medicine'] = Medicine::where('prescription_id', $ticket['prescription']->id)->get();
            $p_tags = array();
            $p_id = array();
            foreach ($ticket['medicine'] as $medicine){
                $medicine_details = ChemistResource::findorFail($medicine->chemist_resource_id);
                $medicine['details'] = $medicine_details;
                $p_tags[] = $medicine_details->resource_name;
                $p_id[] = $medicine_details->id;
            }
            $ticket['medicine_tags'] = $p_tags;
            $ticket['medicine_tagsId'] = $p_id;
        }
        return Response::json($ticket);
    }

    public function selectedTicketInpatient($ticket_id){
        $ticket = Ticket::findorFail($ticket_id);
        $ticket['assigned_by'] = User::findorFail($ticket->issued_by);
        $ticket['assigned_to'] = User::findorFail($ticket->assigned_to);
        $ticket['progress'] = Progress::where('ticket_id', $ticket_id)->latest()->first();
        $ticket['client'] = Clients::findorFail($ticket->client_id);
        $ticket['lab_datas'] = LabData::where('ticket_id', $ticket_id)->get();
        $ticket['symptoms'] = Symptom::where('ticket_id', $ticket_id)->get();
        $ticket['prescription'] = Prescription::where('ticket_id', $ticket_id)->get();
        $ticket['pre_examination'] = GeneralCondition::where('ticket_id', $ticket_id)->get();
        $ticket['special_case'] = SpecialCase::where('client_id', $ticket['client']->id)->first();
        $ticket['inPatient'] = InPatient::where('ticket_id', $ticket_id)->first();
        if ($ticket['pre_examination']){
            foreach ($ticket['pre_examination'] as $examination){
                $examination['details'] = GeneralConditionResource::findorFail($examination->nurse_station_resource_id);
            }
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
            foreach ($ticket['lab_datas'] as $lab_data){
                $lab_data['tests'] = Test::where('lab_id', $lab_data->id)->get();
                foreach ($lab_data['tests'] as $test){
                    //dd($test->lab_resource_id);
                    $test['details'] = LabResource::findorFail($test->lab_resource_id);

                }
            }
        }
        //implode prescription as tags
        if ($ticket['prescription'] != null){
            $ticket['med'] = 'huh';
            foreach ($ticket['prescription'] as $data){
                $data['medicine'] = Medicine::where('prescription_id', $data->id)->get();
                foreach ($data['medicine'] as $medicine){
                    $medicine['details'] = ChemistResource::findorFail($medicine->chemist_resource_id);
                }
            }
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
        //dd('huh');
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
                $test_details = LabResource::findorFail($item);
                $data = new Test(array(
                    'lab_id'=>$startLab->id,
                    'lab_resource_id'=>$item,
                    'amount'=>$test_details->unit_price
                ));
                $data->save();
            }

            return Response::json($startLab);
        }else{
            //delete previous tests
            $toDelete = Test::where('lab_id', $request->labdatas_id)->delete();
            //save lab tests
            if (!empty($request->tests)) {
                $test = explode(',', $request->tests);
                foreach ($test as $item) {
                    $test_details = LabResource::findorFail($item);
                    $data = new Test(array(
                        'lab_id' => $request->labdatas_id,
                        'lab_resource_id' => $item,
                        'amount'=>$test_details->unit_price
                    ));
                    $data->save();
                }
            }
            $startLab = LabData::findorFail($request->labdatas_id);
            return Response::json($startLab);
        }

        //ya ..yaya...too tired to write json response. ill just capture http response code. ...zzzz
    }
    public function startLabInpatient(Request $request){
        //start Lab Ticket
        if($request->labdatas_id == 'null'){
            $startLab = new LabData(array(
                'ticket_id'=>$request->ticket_id,
                'assigned_to'=>$request->technician,
                'status'=>-1,
                'type'=>1
            ));
            $startLab->save();

            //save lab tests
            $test = explode(',', $request->tests);
            foreach ($test as $item){
                $test_details = LabResource::findorFail($item);
                $data = new Test(array(
                    'lab_id'=>$startLab->id,
                    'lab_resource_id'=>$item,
                    'amount'=>$test_details->unit_price
                ));
                $data->save();
            }
        }else{
            $toDelete = Test::where('lab_id', $request->labdatas_id)->delete();
            //save lab tests
            if (!empty($request->tests)) {
                $test = explode(',', $request->tests);
                foreach ($test as $item) {
                    $test_details = LabResource::findorFail($item);
                    $data = new Test(array(
                        'lab_id' => $request->labdatas_id,
                        'lab_resource_id' => $item,
                        'amount'=>$test_details->unit_price
                    ));
                    $data->save();
                }
            }
            $startLab = LabData::findorFail($request->labdatas_id);
        }


        return Response::json($startLab);
    }

    public function sendLab(Request $request){
        //update lab datas table
        DB::table('lab_datas')
            ->where('id', $request->labdatas_id)
            ->update(['status'=>-1]);

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

    public function sendLabInpatient(Request $request){
        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$request->ticket_id,
            'user_id'=>Auth::user()->id,
            'level'=>7,
            'description'=>'Inpatient at Lab'));
        $progress->save();
        $progress = Progress::where('ticket_id', $request->ticket_id)->latest()->first();
        return Response::json($progress);
    }

    public function startChemist(Request $request){
        if ($request->prescription_id == 'none'){
            $prescription = new Prescription(array(
                'ticket_id'=>$request->ticket_id,
                'assigned_to'=>8,
                'status'=>1,
                'type'=>0
            ));
            $prescription->save();
            //save requested medications
            $medication = explode(',', $request->med);
            foreach ($medication as $item){
                $medicine_resource = ChemistResource::findorFail($item);
                $data = new Medicine(array(
                    'prescription_id'=>$prescription->id,
                    'chemist_resource_id'=>$item,
                    'status'=>'pending',
                    'amount'=>$medicine_resource->unit_price
                ));
                $data->save();
            }
            return Response::json($prescription);
        }else{
            $toDelete = Medicine::where('prescription_id', $request->prescription_id)->delete();
            //save medication
            if (!empty($request->med)) {
                $medication = explode(',', $request->med);
                foreach ($medication as $item) {
                    $medicine_resource = ChemistResource::findorFail($item);
                    $data = new Medicine(array(
                        'prescription_id' => $request->prescription_id,
                        'chemist_resource_id' => $item,
                        'status' => 'pending',
                        'amount'=>$medicine_resource->unit_price
                    ));
                    $data->save();
                }
            }
            $prescription = Prescription::findorFail($request->prescription_id);
            return Response::json($prescription);
        }
    }

    public function startChemistInpatient(Request $request){
        if ($request->prescription_id == 'none'){
            $prescription = new Prescription(array(
                'ticket_id'=>$request->ticket_id,
                'assigned_to'=>8,
                'status'=>1,
                'type'=>1
            ));
            $prescription->save();
            //save requested medications
            $medication = explode(',', $request->med);
            foreach ($medication as $item){
                $medicine_resource = ChemistResource::findorFail($item);
                $data = new Medicine(array(
                    'prescription_id'=>$prescription->id,
                    'chemist_resource_id'=>$item,
                    'status'=>'pending',
                    'amount'=>$medicine_resource->unit_price
                ));
                $data->save();
            }
            return Response::json($prescription);
        }else{
            $toDelete = Medicine::where('prescription_id', $request->prescription_id)->delete();
            //save medication
            if (!empty($request->med)) {
                $medication = explode(',', $request->med);
                foreach ($medication as $item) {
                    $medicine_resource = ChemistResource::findorFail($item);
                    $data = new Medicine(array(
                        'prescription_id' => $request->prescription_id,
                        'chemist_resource_id' => $item,
                        'status' => 'pending',
                        'amount'=>$medicine_resource->unit_price
                    ));
                    $data->save();
                }
            }
            $prescription = Prescription::findorFail($request->prescription_id);
            return Response::json($prescription);
        }
    }

    /**
     * close a given ticket completely
     * @param $ticket_id
     * @return mixed
     */
    public function closeTicket($ticket_id){
        $ticket = Ticket::findorFail($ticket_id);
        $ticket->update(['status'=>'closed']);

        return Response::json(array('status'=>'success'));

    }
    public function rTickets(){
        $tickets = Ticket::where('issued_by', Auth::user()->id)->where('status', 'open')->get();
        foreach ($tickets as $ticket){
            $ticket['client'] = Clients::findorFail($ticket->client_id);
            $ticket['doctor'] = User::findorFail($ticket->assigned_to);
        }

        return Response::json($tickets);
    }
}
