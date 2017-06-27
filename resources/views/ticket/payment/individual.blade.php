@extends('layouts.app')

@section('content')
    <div class="row" id="printable" style="text-align: center">
        <h1 style="text-align: center">iHospital</h1>
        <h4 style="text-align: center">Payment Receipt</h4>
        <div class="row" style="text-align: left">
            <div class="row" style="text-align: right">
                Ticket Number: #<br>
                Ticket created on: <br>
                Doctor in-charge: <br>
            </div>
            <div class="row">
                Client Name: <br>
            </div>

        </div>
        <table class="table table-striped table-bordered dt-responsive"
               cellspacing="0" width="100%" style="font-size: 10px">
            <thead>
            <tr>
                <td>Description</td>
                <td>Amount</td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="test in currentPayment.tests">
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Total</b></td>
                <td><b></b></td>
            </tr>
            </tbody>
        </table>
        <div class="row" style="text-align: left">
            Amount Paid: <br>
            Payment Method:
        </div>
    </div>
@endsection
