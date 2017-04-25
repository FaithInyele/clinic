@extends('layouts.app')
@section('content')
    <div class="row">
        <listusers></listusers>
        {{--<table class="table table-striped table-bordered dt-responsive" id="dataTable"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->first_name}}</td>
                    <td>{{$user->last_name}}</td>
                    <td>{{$user->role}}</td>
                    <td><a href="" class="btn btn-danger">De-activate</a> </td>
                </tr>
            @endforeach
            </tbody>
        </table>--}}
    </div>

@endsection
