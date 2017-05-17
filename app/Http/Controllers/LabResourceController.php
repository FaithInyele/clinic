<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabResourceController extends Controller
{
    public function index(){
        $title = 'iHospital | Lab Resources';
        $rightbar = 'resources';

        return view('resources.lab.index', compact('title', 'rightbar'));
    }
}
