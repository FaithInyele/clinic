<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Preference;
use Illuminate\Support\Facades\Response;
use App\ServiceFee;
use Illuminate\Support\Facades\DB;

class PreferencesController extends Controller
{
    public function index(){
        $title = 'iHospital | Preferences';
        $rightbar = 'preferences';

        return view('preferences.index', compact('title', 'rightbar'));
    }

    public function add(Request $request){
        $request['status'] = 1;
        $preference = new Preference($request->all());
        $preference->save();

        return Response::json($request->all());
    }
    public function all(){
        $preferences = Preference::all();
        foreach ($preferences as $preference){
            $preference['fee'] = ServiceFee::where('preference_id', $preference->id)->first();
        }

        return Response::json($preferences);
    }
    public function serviceFee(){
        $title = 'iHospital | Preferences';
        $rightbar = 'preferences';

        return view('preferences.service-fee', compact('title', 'rightbar'));
    }
    public function edit(Request $request){
        DB::table('preferences')
            ->where('id', $request->id)
            ->update(['name'=>$request->name, 'description'=>$request->description]);

        if (isset($request->amount)){
            if ($service_fee = ServiceFee::where('preference_id', $request->id)->exists()){
                DB::table('service_fees')
                    ->where('preference_id', $request->id)
                    ->update(['amount'=>$request->amount]);
            }else{
                $new_fee = new ServiceFee(array('preference_id'=>$request->id, 'amount'=>$request->amount));
                $new_fee->save();
            }
        }
        return Response::json($request->all());
    }
}
