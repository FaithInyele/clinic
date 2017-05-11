<?php

namespace App\Http\Controllers;

use App\LabData;
use Illuminate\Http\Request;
use App\Ticket;
use App\Progress;
use App\Clients;
use App\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class LabController extends Controller
{
    /**
     * retrieve all data related to a given ticket, -->for lab view
     *
     * @param $ticket_id
     * @return mixed
     */
    public function openTicket($ticket_id){
        //dd('elm');
        $ticket = Ticket::findorFail($ticket_id);
        $ticket['progress'] = Progress::where('ticket_id', $ticket_id)->get();
        $ticket['client'] = Clients::findorFail($ticket->client_id);
        $ticket['lab_data'] = LabData::where('ticket_id', $ticket_id)->first();
        $ticket['tests'] = Test::where('lab_id', $ticket['lab_data']->id)->get();

        return Response::json($ticket);
    }

    /**
     * update tests--> adding test results
     *
     * @param Request $request
     * @return mixed
     */
    public function updateTest(Request $request){
        $data = array();
        foreach ($request->all() as $item){
            DB::table('tests')
                ->where('id', $item['id'])
                ->update(['result'=>$item['result']]);
        }
        return Response::json($data);
    }

    public function finishTest($lab_id, Request $request){
        //dd($lab_id);
        DB::table('lab_datas')
            ->where('id', $lab_id)
            ->update(['status'=> 1]);

        //update progress
        $progress = new Progress(array(
            'ticket_id'=>$request->ticket_id,
            'user_id'=>Auth::user()->id,
            'description'=>'Results Available'));
        $progress->save();

        return Response::json(array('success'=>'success'));
    }
}
