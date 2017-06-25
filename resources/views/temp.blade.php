@extends('layouts.app')

@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->role != 'Admin')
    <home></home>
    <div class="row">
        <div class="col-md-8">
            <div class="row raisedbox">
                <div id="test" style="min-width: 310px;max-width: 800px;height: 400px;margin: 0 auto"></div>
            </div>
            <!--Js High CHarts-->
        </div>
        @if(\Illuminate\Support\Facades\Auth::user()->role == 'Doctor')
            <div class="col-md-4 raisedbox">
                <label>My Consultations</label>
                <sidebar></sidebar>
            </div>
        @endif
    </div>
    @else
        <div class="row">
            <admin></admin>
        </div>
    @endif
@endsection