<?php

namespace App\Http\Controllers;

use App\ChemistResource;
use App\LabResource;
use App\Medicine;
use App\Test;
use Illuminate\Http\Request;
use App\LabData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class ProgressController extends Controller
{

    public function atDoctor(){

    }
    /**
     * get all active tickets, currently awaiting lab tests and results.
     *
     * @return mixed
     */
    public function atLab(){
        $atLab = DB::table('tickets')
            ->join('lab_datas', 'tickets.id', '=', 'lab_datas.ticket_id')
            ->join('clients', 'tickets.client_id', '=', 'clients.id')
            ->join('users', 'tickets.assigned_to', '=', 'users.id')
            ->select('lab_datas.*',
                'clients.first_name as c_fname',
                'clients.other_names as c_othernames')
            //->where('lab_datas.assigned_to', '=', 7)
            ->where('lab_datas.status', '=', 0)
            ->get();
        foreach ($atLab as $data){
            $tests_details = Test::where('lab_id', $data->id)->get();
            //dd($tests_details);
            $tests = array();
            //dd($ticket['tests']);
            foreach ($tests_details as $test){
                //dd($test->lab_resource_id);
                $test_details = LabResource::findorFail($test->lab_resource_id);
                $tests[] = $test_details->resource_name;
            }
            $data->tests = implode(',', $tests);
        }

        return Response::json($atLab);
    }

    /**
     * get all active tickets, currently awaiting medical prescriptions
     *
     * @return mixed
     */
    public function atChemist(){
        //dd('huh');
        $atChemist = DB::table('tickets')
            ->join('prescriptions', 'tickets.id', '=', 'prescriptions.ticket_id')
            ->join('clients', 'tickets.client_id', '=', 'clients.id')
            ->join('users', 'tickets.assigned_to', '=', 'users.id')
            ->select('prescriptions.*',
                'clients.first_name as c_fname',
                'clients.other_names as c_othernames')
            //->where('lab_datas.assigned_to', '=', 7)
            ->where('prescriptions.status', '=', 1)
            ->get();
        foreach ($atChemist as $data){
            $tests_details = Medicine::where('prescription_id', $data->id)->get();
            //dd($tests_details);
            $tests = array();
            //dd($ticket['tests']);
            foreach ($tests_details as $test){
                //dd($test->lab_resource_id);
                $test_details = ChemistResource::findorFail($test->chemist_resource_id);
                $tests[] = $test_details->resource_name;
            }
            $data->tests = implode(',', $tests);
        }

        return Response::json($atChemist);

    }
}
