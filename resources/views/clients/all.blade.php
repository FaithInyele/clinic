@extends('layouts.app')
@section('content')
    <div class="row">
        <listclients></listclients>
        {{--<table class="table table-striped table-bordered dt-responsive" id="dataTable"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Other Name(s)</th>
                <th>D.O.B</th>
                <th>Type</th>
                <th>Gender</th>
                <th>ID No.</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{$client->first_name}}</td>
                    <td>{{$client->other_names}}</td>
                    <td>{{$client->yob}}</td>
                    <td>{{$client->type}}</td>
                    <td>{{$client->gender}}</td>
                    <td>{{$client->id_number}}</td>
                    <td><a href="" class="btn btn-danger"><i class="fa fa-remove"></i> Delete</a> <a @click="clientEdit=true" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>--}}
    </div>
    <example v-show="clientEdit" @close="clientEdit = false">

    </example>

@endsection
