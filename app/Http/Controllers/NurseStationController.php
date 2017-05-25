<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GeneralConditionResource;
use Illuminate\Support\Facades\Response;

class NurseStationController extends Controller
{
    public function index(){
        $title = 'iHospital | Nurse Station Resources';
        $rightbar = 'resources';

        return view('resources.nurse-station.index', compact('title', 'rightbar'));
    }
    public function all(){
        $resources = GeneralConditionResource::all();

        return Response::json($resources);
    }

    public function addNew(Request $request){
        $request['status'] =  1;
        $resource = new GeneralConditionResource($request->all());
        $resource->save();

        return Response::json($resource);
    }
}
