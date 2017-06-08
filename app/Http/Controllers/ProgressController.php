<?php

namespace App\Http\Controllers;

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
                'clients.first_name as c_fnameee',
                'clients.other_names as c_othernames')
            //->where('lab_datas.assigned_to', '=', 7)
            ->where('lab_datas.status', '=', 0)
            ->get();

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

        return Response::json($atChemist);

    }
}
