<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients;
use App\SpecialCase;
use Illuminate\Support\Facades\Response;
use App\Ticket;
use Illuminate\Support\Facades\DB;
use App\Preference;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'iHospital | Clients';
        $rightbar = 'client';
        $clients = Clients::all();
        //dd($users);

        return view('clients.all', compact('clients', 'rightbar', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'iHospital | Add New Client';
        $rightbar = 'client';
        $total_clients = count(Clients::all());
        $active_tickets = count(Ticket::where('status', 'open')->get());
        $registration_fee = Preference::where('name', 'Registration Fee')->first();
        //dd($registration_fee);

        return view('clients.add', compact('rightbar', 'title', 'total_clients', 'active_tickets', 'registration_fee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'reg_number' => 'unique:clients',
        ]);
        //save user to db
        $client = new Clients($request->all());
        $client->save();

        //return success message to page
        return redirect()->action('ClientsController@create')
            ->with('status', $request->first_name.' Successfully Registered to the System.')
            ->with('newClient', $client);
    }

    /**
     * Display the specified resource.(client)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Clients::findorFail($id);

        return Response::json($client);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::table('clients')
            ->where('id', $request->id)
            ->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    /**
     * Open up the special medical condition form for a recently added client
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function special_condition(Request $request){

        $title = 'iHospital | Special Medical Conditions';
        $rightbar = 'client';

        $client_id = $request->client_id;
        $client_name = $request->client_name;

        return view('clients.special_condition.open', compact('title', 'rightbar', 'client_id', 'client_name'));
    }

    /**
     * add a client's special medical condition to the system
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function special_condition_save(Request $request){

        //dd($request->all());
        $special = new SpecialCase($request->all());
        $special->save();

        return redirect()->action('ClientsController@create')
            ->with('status', 'Special Medical Condition(s) Added.');
    }

    public function listAll(){
        $clients = Clients::all();

        return Response::json($clients);
    }
}
