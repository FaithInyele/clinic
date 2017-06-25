@extends('layouts.app')

@section('content')
    <h3>All Payments</h3>
    <hr>
    <table class="table table-striped table-bordered dt-responsive" id="dataTable"
           cellspacing="0" width="100%" style="font-size: 10px">
        <thead>
        <tr>
            <th>Payment No.</th>
            <th>Client</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Dated</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payments as $ticket)
            <tr>
                <td>{{$ticket->id}}</td>
                <td>{{$ticket->client->first_name}}, {{$ticket->client->other_names}} </td>
                <td>{{$ticket->type}} </td>
                <td>{{$ticket->amount}}</td>
                <td>{{$ticket->payment_method}}</td>
                <td>{{$ticket->created_at}}</td>
                <td><a href="" class="btn btn-info"><i class="fa fa-print">Print</i></a></td>

            </tr>
        @endforeach

        </tbody>
    </table>
@endsection
