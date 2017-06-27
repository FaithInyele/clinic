@extends('layouts.app')

@section('content')
    <div class="col-lg-8">

        <form class="uk-form" role="form" method="POST" action="{{url('users/add')}}">
            {{ csrf_field() }}

            <!--firstname-->
            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                <label for="first_name" class="col-md-4 control-label">First Name</label><br>

                <div class="row">
                    <input id="first_name" type="text"  class="validate[required] form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>

                    @if ($errors->has('first_name'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

                <!--lastname-->
                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <label for="last_name" class="col-md-4 control-label">Last Name</label><br>

                    <div class="row">
                        <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus>

                        @if ($errors->has('last_name'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <!--idnumber-->
                <div class="form-group{{ $errors->has('id_no') ? ' has-error' : '' }}">
                    <label for="id_no" class="row control-label">National Identification Number</label><br>

                    <div class="row">
                        <input id="id_no" type="number" maxlength="12" minlength="8" class="form-control" name="id_no" value="{{ old('id_no') }}" required autofocus>

                        @if ($errors->has('id_no'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('id_no') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>


                <!--gender-->
                <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                    <label for="gender" class="col-md-4 control-label">Gender</label><br>

                    <div class="row">
                        <select id="gender" name="gender" class="form-control">
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
                    <label for="yob" class="row control-label">Year of Birth</label><br>

                    <div class="row">
                        <input id="yob" type="text" class="form-control" name="yob" value="{{ old('yob') }}" required autofocus>

                        @if ($errors->has('yob'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('yob') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <!--email address-->
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                <div class="row">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

                <!--role-->
                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                    <label for="role" class="col-md-4 control-label">User Role</label><br>

                    <div class="row">
                        <select id="role" name="role" class="form-control">
                            <option value="Receptionist">Receptionist</option>
                            <option value="Lab Technician">Lab Technician</option>
                            <option value="Chemist">Chemist</option>
                            <option value="Nurse">Nurse</option>
                            <option value="Doctor">Doctor</option>
                            <option value="Admin">Admin</option>
                        </select>

                        @if ($errors->has('role'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">Password</label>

                <div class="row">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                <div class="row">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <button type="submit" class="btn btn-primary form-control">
                        Register
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-4" style="padding-top: 15px">
        <div class="alert alert-info">
            <i class="pull-right fa fa-question-circle"></i>
            <strong>Help</strong> <br>
            <ol>
                <li>This Page is Used to Add New Users to the System.</li>
                <li>Ensure All fields with a (*) are NOT left blank</li>
                <li>Ensure all data is valid</li>
                <li>The newly registered user's account will be activated immediately after registration</li>
            </ol>
        </div>
        <!-- .well well-small -->
        <div class="well well-small dark">
            <strong>Statistics</strong> <br>
            <ul class="list-unstyled">
                <li>My Facility <span class="inlinesparkline pull-right"></span></li>
                <hr>
                <li>Active Users <span class="dynamicbar pull-right">{{ $active_users }}</span></li>
                <li>Inactive Users <span class="inlinebar pull-right"> {{ $inactive_users }} </span></li>
                <hr>
                <li>Total Users <span class="dynamicsparkline pull-right">{{$total_users}}</span></li>
            </ul>
        </div>
        <!-- /.well well-small -->
    </div>
@endsection
