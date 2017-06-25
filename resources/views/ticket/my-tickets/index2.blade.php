@extends('layouts.app')

@section('content')
    @if(Auth::user()->role == 'Receptionist')
        <!--receptionist components-->
        <r_tickets></r_tickets>
    @endif

    @if(Auth::user()->role == 'Doctor')
        <!--nurse/doctor components-->
        <atdoctor></atdoctor>
    @endif

    @if(Auth::user()->role == 'Lab Technician')
        <!--lab technician components-->
        <atlab></atlab>
    @endif

    @if(Auth::user()->role == 'Chemist')
        <!--chemist components-->
        <chemist></chemist>
    @endif
@endsection
