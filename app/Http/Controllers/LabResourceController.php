<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LabResource;
use Illuminate\Support\Facades\Response;

class LabResourceController extends Controller
{
    /**
     * load page for displaying and manipulation or Lab resources
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $title = 'iHospital | Lab Resources';
        $rightbar = 'resources';

        return view('resources.lab.index', compact('title', 'rightbar'));
    }

    /**
     * API call to get all Lab resources
     * @return mixed
     */
    public function all(){
        $resources = LabResource::all();

        return Response::json($resources);
    }

    public function addNew(Request $request){
        $resource = new LabResource($request->all());
        $resource->save();

        return Response::json($resource);
    }
    public function edit(Request $request){
        $resource = LabResource::findorFail($request->resource_id);
        $resource->update($request->all());

        return Response::json($resource);
    }
}
