@extends('layouts.app')

@section('content')
    <div class="col-lg-8">
        <ticket_start></ticket_start>
        <ticket v-show="startTicket" @close="startTicket = false">

        </ticket>
    </div>
    <div class="col-lg-4" style="padding-top: 15px">
        @include('ticket/rightbar')
    </div>
@endsection
