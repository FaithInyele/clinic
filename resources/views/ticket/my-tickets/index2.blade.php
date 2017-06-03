@extends('layouts.app')

@section('content')
    @if(Auth::user()->role == 'Receptionist')
        <!--receptionist components-->
        <ticket_start></ticket_start>
    @endif

    @if(Auth::user()->role == 'Nurse/Doctor')
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
