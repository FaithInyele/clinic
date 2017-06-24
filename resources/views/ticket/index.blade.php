@extends('layouts.app')

@section('content')
    <div class="row">
        <h5>All Tickets</h5>
        <hr>
        <table class="table table-striped table-bordered dt-responsive" id="dataTable"
               cellspacing="0" width="100%" style="font-size: 10px">
            <thead>
            <tr>
                <th>Ticket Id</th>
                <th>Client</th>
                <th>Assigned By</th>
                <th>Assigned To</th>
                <th>Created On</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{$ticket->id}}</td>
                <td>{{$ticket->client->first_name}}, {{$ticket->client->other_names}} </td>
                <td>{{$ticket->reception->first_name}}, {{$ticket->reception->last_name}} </td>
                <td>{{$ticket->doctor->first_name}}, {{$ticket->doctor->last_name}}</td>
                <td>{{$ticket->created_at}}</td>
                @if($ticket->status == 'open')
                    <td style="color: red">{{$ticket->status}}</td>
                @else
                    <td style="color: green">{{$ticket->status}}</td>
                @endif

            </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection