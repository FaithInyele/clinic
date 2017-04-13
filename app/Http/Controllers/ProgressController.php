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

        return Response::json($atLab);
    }

    public function atChemist(){

    }
}
