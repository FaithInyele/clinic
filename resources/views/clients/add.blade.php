@extends('layouts.app')
@section('content')
    <div class="col-lg-8 form-style">
        @if (session('newClient'))
            <div class="row" id="printable" style="text-align: center">
                <h1 style="text-align: center">iHospital</h1>
                <h4 style="text-align: center">Payment Receipt</h4>
                <div class="row" style="text-align: left">
                    <div class="row">
                        Client Name: {{session('newClient')->first_name}}, {{session('newClient')->other_names}}<br>
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
                    <tr>
                        <td>Registration Fee</td>
                        <td>{{session('newClient')->reg_fee}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Registration Payment Receipt</strong> <br>
                <h6>
                    Print <b>{{ session('newClient')->first_name }}'s</b> Registration Receipt. Click <button class="btn btn-sm btn-primary" onclick="printdata('printable')">Here</button>
                </h6>
            </div>
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Special Medical Condition(s)?</strong> <br>
                <h6>
                    if  <b>{{ session('newClient')->first_name }}</b>  has Special Medical Conditions. Please Click <a href="{{url('clients/medical-conditions/open?client_id='.session('newClient')->id.'&client_name='.session('newClient')->first_name)}}">Here</a>
                </h6>
            </div>
        @endif
        <div class="row">
            <div class="row form-box">
                <form class="f1" role="form" method="POST" id="register_client" action="{{url('clients/add')}}">
                    {{ csrf_field() }}
                    <div class="f1-steps">
                        <div class="f1-progress">
                            <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
                        </div>
                        <div class="f1-step active">
                            <div class="f1-step-icon"><i class="fa fa-user"></i></div>
                            <p>Personal Data</p>
                        </div>
                        <div class="f1-step">
                            <div class="f1-step-icon"><i class="fa fa-phone"></i></div>
                            <p>Contact Information</p>
                        </div>
                        <div class="f1-step">
                            <div class="f1-step-icon"><i class="fa fa-mars-double"></i></div>
                            <p>Parents Details</p>
                        </div>
                    </div>
                    <fieldset>
                        <!--first name-->
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-md-4 control-label">First Name*</label><br>
                            <div class="row">
                                <input id="first_name" type="text" class="required form-control" name="first_name" value="{{ old('first_name') }}" autofocus>
                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--last name-->
                        <div class="form-group{{ $errors->has('other_names') ? ' has-error' : '' }}">
                            <label for="other_names" class="col-md-4 control-label">Other Name(s)*</label><br>
                            <div class="row">
                                <input id="other_names" type="text" class="required form-control" name="other_names" value="{{ old('other_names') }}" autofocus>
                                @if ($errors->has('other_names'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('other_names') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--id number-->
                        {{--<div class="form-group{{ $errors->has('id_number') ? ' has-error' : '' }}">--}}
                            {{--<label for="id_number" class="row control-label">ID Number/Birth Certificate Number*</label><br>--}}
                            {{--<div class="row">--}}
                                {{--<input id="id_number" type="text" maxlength="12" minlength="8" onkeypress="return isNumber(event)" class="required form-control" name="id_number" value="{{ old('id_number') }}" autofocus>--}}
                                {{--@if ($errors->has('id_number'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('id_number') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <!--gender-->
                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label for="gender" class="col-md-4 control-label">Gender*</label><br>
                            <div class="row">
                                <select id="gender" name="gender" class="required form-control">
                                    <option value="" disabled selected>-Select Gender-</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--year of birth-->
                        <div class="form-group{{ $errors->has('yob') ? ' has-error' : '' }}">
                            <label for="yob" class="row control-label">Year of Birth*</label><br>
                            <div class="row">
                                <input id="yob" type="date" class="required form-control" name="yob" value="{{ old('yob') }}" autofocus>
                                @if ($errors->has('yob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('yob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--weight in kgs-->
                        <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                        <label for="weight" class="row control-label">Weight in Kgs*</label><br>
                        <div class="row">
                            <input id="weight" type="number" class="required form-control" name="weight" value="{{ old('weight') }}" autofocus>
                            @if ($errors->has('weight'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('weight') }}</strong>
                                    </span>
                            @endif
                        </div>
                        </div>
                        <!--length in cms-->
                       <div class="form-group{{ $errors->has('length') ? ' has-error' : '' }}">
                        <label for="length" class="row control-label">Length in Cm*</label><br>
                        <div class="row">
                            <input id="length" type="number" class="required form-control" name="length" value="{{ old('length') }}" autofocus>
                            @if ($errors->has('length'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('length') }}</strong>
                                    </span>
                            @endif
                        </div>
                         </div>
                        {{--<!--client type-->--}}
                        {{--<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">--}}
                            {{--<label for="type" class="col-md-4 control-label">Client Type*</label><br>--}}
                            {{--<div class="row">--}}
                                {{--<select id="type" name="type" class="form-control required" onchange="reg_numberr()">--}}
                                    {{--<option value="" disabled selected>-Select Client Type-</option>--}}
                                    {{--<option value="Student">Student</option>--}}
                                    {{--<option value="Staff">Staff</option>--}}
                                    {{--<option value="Others">Others</option>--}}
                                {{--</select>--}}
                                {{--@if ($errors->has('type'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('type') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<!--Student Reg Number //optional-->--}}
                        {{--<div class="form-group{{ $errors->has('reg_number') ? ' has-error' : '' }}">--}}
                            {{--<div class="row d_regNumber">--}}
                                {{--@if ($errors->has('reg_number'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('reg_number') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="f1-buttons">
                            <button type="button" class="btn btn-next">Next<i class="fa fa-arrow-right"></i></button>
                        </div>
                    </fieldset>
                    <fieldset>
                        <h4>Client Contact Details:</h4>
                        <!--Phone number-->
                        {{--<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">--}}
                            {{--<label for="phone" class="col-md-4 control-label">Phone Number*</label>--}}
                            {{--<div class="row">--}}
                                {{--<input id="phone" type="text" maxlength="12" minlength="8" onkeypress="return isNumber(event)" class="form-control required" name="phone" value="{{ old('phone') }}" required>--}}
                                {{--@if ($errors->has('phone'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('phone') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <!--Physical Address-->
                        {{--<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">--}}
                            {{--<label for="address" class="col-md-4 control-label">Physical Address</label>--}}
                            {{--<div class="row">--}}
                                {{--<textarea id="address" name="address" class="form-control">--}}
                                    {{--{{ old('address') }}--}}
                                {{--</textarea>--}}
                                {{--@if ($errors->has('address'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('address') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<!--email address-->--}}
                        {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
                        {{--<label for="email" class="col-md-4 control-label">E-Mail Address</label>--}}
                        {{--<div class="row">--}}
                        {{--<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">--}}
                        {{--@if ($errors->has('email'))--}}
                        {{--<span class="help-block">--}}
                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                        {{--</span>--}}
                        {{--@endif--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <!--county-->
                        <div class="form-group{{ $errors->has('county') ? ' has-error' : '' }}">
                        <label for="county" class="col-md-4 control-label">County</label>
                        <div class="row">
                        <input id="county" type="text" class="form-control" name="county" value="{{ old('county') }}">
                        @if ($errors->has('county'))
                        <span class="help-block">
                        <strong>{{ $errors->first('county') }}</strong>
                        </span>
                        @endif
                        </div>
                        </div>

                        <!--sub-county-->
                        <div class="form-group{{ $errors->has('sub-county') ? ' has-error' : '' }}">
                            <label for="sub-county" class="col-md-4 control-label">Sub-County</label>
                            <div class="row">
                                <input id="sub-county" type="text" class="form-control" name="sub-county" value="{{ old('sub-county') }}">
                                @if ($errors->has('sub-county'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('sub-county') }}</strong>
                        </span>
                                @endif
                            </div>
                        </div>

                        <!--town-->
                        <div class="form-group{{ $errors->has('town') ? ' has-error' : '' }}">
                            <label for="town" class="col-md-4 control-label">Town</label>
                            <div class="row">
                                <input id="town" type="text" class="form-control" name="town" value="{{ old('town') }}">
                                @if ($errors->has('town'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('town') }}</strong>
                        </span>
                                @endif
                            </div>
                        </div>

                        <!--county-->
                        <div class="form-group{{ $errors->has('village') ? ' has-error' : '' }}">
                            <label for="village" class="col-md-4 control-label">Village</label>
                            <div class="row">
                                <input id="village" type="text" class="form-control" name="village" value="{{ old('village') }}">
                                @if ($errors->has('village'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('village') }}</strong>
                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="f1-buttons">
                            <button type="button" class="btn btn-previous"><i class="fa fa-arrow-left"></i> Previous</button>
                            <button type="button" class="btn btn-next">Next<i class="fa fa-arrow-right"></i></button>
                        </div>
                    </fieldset>
                    <fieldset>
                        <h4>Parents details:</h4>
                        <!--keen relationship-->
                        {{--<div class="form-group{{ $errors->has('keen_type') ? ' has-error' : '' }}">--}}
                            {{--<label for="keen_type" class="col-md-4 control-label">Relationship with Next of Keen</label>--}}
                            {{--<div class="row">--}}
                                {{--<select id="keen_type" name="keen_type" class="form-control">--}}
                                    {{--<option value="" selected disabled>-Relationship with Keen-</option>--}}
                                    {{--<option value="Parent">Parent</option>--}}
                                    {{--<option value="Child">Child</option>--}}
                                    {{--<option value="Spouse">Spouse</option>--}}
                                    {{--<option value="Brother/Sister">Brother/Sister</option>--}}
                                    {{--<option value="Uncle/Aunt">Uncle/Aunt</option>--}}
                                    {{--<option value="Other">Other</option>--}}
                                {{--</select>--}}
                                {{--@if ($errors->has('keen_type'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('keen_type') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <!--mothers names-->
                        <div class="form-group{{ $errors->has('mothers_names') ? ' has-error' : '' }}">
                            <label for="mothers_names" class="row control-label">Mothers Names</label>
                            <div class="row">
                                <input id="mothers_names" type="text" class="form-control" name="mothers_name" value="
                                {{ old('mothers_names') }}">
                                @if ($errors->has('mothers_names'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mothers_names') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--mothers id-->
                        <div class="form-group{{ $errors->has('mothers_id_no') ? ' has-error' : '' }}">
                            <label for="mothers_id_no" class="row control-label">Mothers ID no</label>
                            <div class="row">
                                <input id="mothers_id_no" type="number" name="mothers_id_no" class="form-control">
                                    {{ old('mothers_id_no') }}
                                @if ($errors->has('mothers_id_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mothers_id_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--mothers id-->
                        <div class="form-group{{ $errors->has('mothers_telephone_no') ? ' has-error' : '' }}">
                            <label for="mothers_telephone_no" class="row control-label">Mothers Telephone No</label>
                            <div class="row">
                                <input id="mothers_telephone_no" type="number" name="mothers_telephone_no" class="form-control">
                                    {{ old('mothers_telephone_no') }}
                                @if ($errors->has('mothers_telephone_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mothers_telephone_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--fathers names-->
                        <div class="form-group{{ $errors->has('fathers_names') ? ' has-error' : '' }}">
                            <label for="fathers_names" class="row control-label">Fathers Names</label>
                            <div class="row">
                                <input id="fathers_names" type="text" class="form-control" name="fathers_name" value="{{ old('fathers_names') }}">
                                @if ($errors->has('fathers_names'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('fathers_names') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--fathers id-->
                        <div class="form-group{{ $errors->has('fathers_id_no') ? ' has-error' : '' }}">
                            <label for="fathers_id_no" class="row control-label">Fathers ID no</label>
                            <div class="row">
                                <input id="fathers_id_no" type="number" name="fathers_id_no" class="form-control">
                                    {{ old('fathers_id_no') }}
                                @if ($errors->has('fathers_id_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('fathers_id_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--fathers telephone-->
                        <div class="form-group{{ $errors->has('fathers_telephone_no') ? ' has-error' : '' }}">
                            <label for="fathers_telephone_no" class="row control-label">Fathers Telephone No</label>
                            <div class="row">
                                <input id="fathers_telephone_no" type="number" class="form-control" name="fathers_telephone_no">
                                    {{ old('fathers_telephone_no') }}
                                @if ($errors->has('fathers_telephone_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('fathers_telephone_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--Physical Address-->
                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                    <label for="address" class="col-md-4 control-label">Physical Address</label>
                    <div class="row">
                    <textarea id="address" name="address" class="form-control">
                    {{ old('address') }}
                    </textarea>
                    @if ($errors->has('address'))
                    <span class="help-block">
                    <strong>{{ $errors->first('address') }}</strong>
                    </span>
                    @endif
                    </div>
                    </div>
                        <!--confirm payment -->
                        <div class="form-group">
                            <div class="row" style="font-size: 12px">
                                <input type="checkbox" id="p_check" value="paid" onchange="confirmPayment()">
                                <input type="hidden" id="" value="100" name="reg_fee">
                                Confirm. if Client has Paid the KSH. {{$registration_fee->fee->amount}} Registration fee.
                            </div>
                        </div>
                        <div class="f1-buttons">
                            <button type="button" class="btn btn-primary btn-previous"><i class="fa fa-arrow-left"></i>Previous</button>
                            <button type="submit" id="c_submit" class="btn btn-primary btn-submit">Submit</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4" style="padding-top: 15px">
        {{--<div class="alert alert-info">--}}
            {{--<strong>Help</strong> <i class="fa fa-question-circle pull-right"></i><br>--}}
            {{--<ol>--}}
                {{--<li>This Page is used to Add New Clients to the System. THOSE NOT YET REGISTERED</li>--}}
                {{--<li>All fields with a (*) should NOT be left blank.</li>--}}
                {{--<li>Click Next once all data is filled</li>--}}
                {{--<li>A client registered will be available immediately for Ticket Assignment</li>--}}
                {{--<li>Registration Fee is Ksh. {{$registration_fee->fee->amount}}</li>--}}
            {{--</ol>--}}
        {{--</div>--}}
        <!-- .well well-small -->
        <div class="well well-small dark">
            <strong>Stats</strong> <br>
            <ul class="list-unstyled">
                <li>My Facility <span class="inlinesparkline pull-right"></span></li>
                <hr>
                <li>Total Clients <span class="dynamicbar pull-right">{{ $total_clients }}</span></li>
                <li>Active Tickets <span class="dynamicbar pull-right"> {{ $active_tickets }}</span></li>
                {{--<li>Inactive Users <span class="inlinebar pull-right">4</span></li>
                <hr>
                <li>Total Users <span class="dynamicsparkline pull-right">24</span></li>--}}
            </ul>
        </div>
        <!-- /.well well-small -->
        <!-- .well well-small -->
        <div class="well well-small dark">

        </div>
    </div>
@endsection
