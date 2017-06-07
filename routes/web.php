<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();



//Super Admin Routes

Route::group(['middleware' => 'logIn'], function () {
    Route::get('/', 'HomeController@index');
    /**
     * managing users routes
     */

    Route::post('users/add', 'RegisterController@store');
    Route::get('users/add', 'RegisterController@regForm');
    Route::get('users/view', 'RegisterController@listAll');
    Route::get('users/all', 'RegisterController@allUsers');
    Route::post('users/activate', 'RegisterController@activate');

    /**
     * Managing clients routes
     */
    Route::get('clients', 'ClientsController@index');
    Route::get('clients/add', 'ClientsController@create');
    Route::post('clients/add', 'ClientsController@store');
    Route::get('clients/all', 'ClientsController@listAll');
    Route::post('clients/delete', 'ClientsController@destroy');
    Route::get('clients/{id}', 'ClientsController@show');
    Route::post('clients/edit', 'ClientsController@update');

//special condition routes
    Route::get('clients/medical-conditions/open', 'ClientsController@special_condition');
    Route::post('clients/medical-conditions/open', 'ClientsController@special_condition_save');

    /**
     * Ticket routes
     */
    Route::get('tickets', 'TicketController@index');
    Route::get('tickets/add', 'TicketController@add_form');
    Route::get('tickets/start', 'TicketController@start_form');
    Route::post('tickets/start', 'TicketController@start_store');
    Route::get('tickets/search', 'TicketController@searchClient');
    Route::get('tickets/my-tickets', 'TicketController@myTickets');
    Route::get('tickets/my-tickets/all-active', 'TicketController@allActive');
    Route::get('tickets/my-tickets/{ticket_id}', 'TicketController@selectedTicket');
    Route::get('tickets/my-tickets/save/symptoms', 'TicketController@saveSymptoms');
    Route::get('tickets/my-tickets/query/labtechs', 'TicketController@activeLabTechnicians');
    Route::get('tickets/my-tickets/query/startlab', 'TicketController@startLab');
    Route::get('tickets/my-tickets/query/sendlab', 'TicketController@sendLab');
    Route::get('tickets/my-tickets/query/startchemist', 'TicketController@startChemist');
    Route::get('tickets/my-tickets/all/doctors', 'TicketController@activeDoc');
    Route::get('tickets/my-tickets/close/{ticket_id}', 'TicketController@closeTicket');
    //payments
    Route::get('tickets/payments', 'PaymentController@index');
    Route::get('tickets/payments/pending', 'PaymentController@pending');
    Route::post('tickets/payments/pay-lab', 'PaymentController@payLab');

    /**
     * consultation routes
     */
    Route::get('tickets/consultations', 'ChatController@index');

    /**
     * Progress routes
     */
    Route::get('progress/atlab', 'ProgressController@atLab');
    Route::get('progress/atchemist', 'ProgressController@atChemist');


    /**
     * atLab routes.
     */
    Route::get('atlab/view/{ticket_id}', 'LabController@openTicket');
    Route::post('atlab/test/update', 'LabController@updateTest');
    Route::get('atlab/lab/update/{lab_id}', 'LabController@finishTest');

    /**
     * atChemist routes
     */
    Route::get('atchemist/view/{ticket_id}', 'ChemistController@currentTicket');
    Route::get('atchemist/submit/{prescription_id}', 'ChemistController@submitPrescription');
    Route::get('atchemist/close', 'ChemistController@closePrescription');
    Route::post('atchemist/update', 'ChemistController@updatePrescription');

    /**
     * resources routes
     */
    //lab resources
    Route::get('resources/lab', 'LabResourceController@index');
    Route::get('resources/lab/all', 'LabResourceController@all');
    Route::post('resources/lab/new', 'LabResourceController@addNew');
    //chemist resources
    Route::get('resources/chemist', 'ChemistResourceController@index');
    Route::get('resources/chemist/all', 'ChemistResourceController@all');
    Route::post('resources/chemist/new', 'ChemistResourceController@addNew');
    //nurse station routes
    Route::get('resources/nurse-station', 'NurseStationController@index');
    Route::get('resources/nurse-station/all', 'NurseStationController@all');
    Route::post('resources/nurse-station/new', 'NurseStationController@addNew');
    Route::get('resources/nurse-station/update', 'NurseStationController@update');



    Route::get('search/test', 'LabController@search');
    Route::get('search/prescription', 'ChemistController@search');

    /**
     * preference routes
     */
    Route::get('preferences', 'PreferencesController@index');
    Route::post('preferences/add', 'PreferencesController@add');
    Route::get('preferences/all', 'PreferencesController@all');
    Route::get('preferences/service-fee', 'PreferencesController@serviceFee');
    Route::post('preferences/edit', 'PreferencesController@edit');

    /**
     * chat routes
     */
    Route::get('chat/start', 'ChatController@start');
    Route::get('chat/unread', 'ChatController@unread');
    Route::get('chat/allmessages', 'ChatController@allMessages');
    Route::post('chat/newmessage', 'ChatController@newMessage');

    /**
     * inpatient routes
     */
    Route::get('inpatient/admit/{ticket_id}', 'InPatientController@admit');
    Route::get('inpatient/all', 'TicketController@inPatient');


});


