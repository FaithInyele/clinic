<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChemistResource;
use Illuminate\Support\Facades\Response;

class ChemistResourceController extends Controller
{
    /**
     * load page for displaying and manipulation of chemist resources
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $title = 'iHospital | Chemist Resources';
        $rightbar = 'resources';

        return view('resources.chemist.index', compact('title', 'rightbar'));
    }
    /**
     * API call to get all Lab resources
     * @return mixed
     */
    public function all(){
        $resources = ChemistResource::all();

        return Response::json($resources);
    }

    public function addNew(Request $request){
        $resource = new ChemistResource($request->all());
        $resource->save();

        return Response::json($resource);
    }

}
