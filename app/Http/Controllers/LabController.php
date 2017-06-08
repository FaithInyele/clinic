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
use App\LabResource;

class LabController extends Controller
{
    /**
     * retrieve all data related to a given ticket, -->for lab view
     * its actualy lab datas id not ticket id as mentioned
     * @param $ticket_id
     * @return mixed
     */
    public function openTicket($ticket_id){
        //dd('elm');
        $data= LabData::findorFail($ticket_id);
        $data['ticket'] = Ticket::findorFail($data->ticket_id);
        $data['progress'] = Progress::where('ticket_id', $data['ticket']->id)->latest()->first();
        $data['client'] = Clients::findorFail($data['ticket']->client_id);
        $data['tests'] = Test::where('lab_id', $ticket_id)->get();
        if ($data['tests']){
            foreach ($data['tests'] as $test){
                $test['details'] = LabResource::findorFail($test->lab_resource_id);
            }
        }

        return Response::json($data);
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

    /**
     * mwthod called by lab tech to finish tests and submit back to doctor
     * @param $lab_id
     * @param Request $request
     * @return mixed
     */
    public function finishTest($lab_id, Request $request){
        //dd($lab_id);
        $labtest = LabData::findorFail($lab_id);
        DB::table('lab_datas')
            ->where('id', $lab_id)
            ->update(['status'=>1]);
        if ($labtest->type == 0){
            //update progress
            $progress = new Progress(array(
                'ticket_id'=>$request->ticket_id,
                'user_id'=>Auth::user()->id,
                'level'=>3,
                'description'=>'Results Available'));
            $progress->save();
        }else{
            //update progress
            $progress = new Progress(array(
                'ticket_id'=>$request->ticket_id,
                'user_id'=>Auth::user()->id,
                'level'=>7,
                'description'=>'Inpatient Results Available'));
            $progress->save();
        }

        return Response::json($labtest);
    }

    /**
     * method to searh for lab tests //vue api
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request){
        $results = LabResource::where('resource_name', 'LIKE', '%'.$request->q.'%')
            ->orwhere('description', 'LIKE', '%'.$request->q.'%')
            ->get();
        if ($request->labdatas_id == 'null'){
            foreach ($results as $result){
                $result['status'] = false;
            }
        }else{
            foreach ($results as $result){
                if ($test = Test::where('lab_id', $request->labdatas_id)
                    ->where('lab_resource_id', $result->id)->exists()){
                    $result['status'] = true;
                }else{
                    $result['status'] = false;
                }
            }
        }
        return Response::json($results);
    }
}
