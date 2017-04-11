@extends('layouts.app')

@section('content')
    <div class="col-lg-8">

        <h4>
            Assign this Client to an available Nurse/Doctor
        </h4>

        <form class="uk-form" role="form" method="POST" action="{{url('tickets/start')}}">
        {{ csrf_field() }}

            <div class="form-group">
                <label for="gender" class="col-md-4 control-label">Client Name</label><br>

                <div class="row">

                    <input type="hidden" name="client_id" class="form-control" value="{{ $data['client_id'] }}">
                    <input type="text" name="client_name" class="form-control" disabled value="{{ $data['client_fname'] }}">
                </div>
            </div>

            <!--assigned to-->
            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                <label for="gender" class="col-md-4 control-label">Select Doctor/Nurse</label><br>

                <div class="row">
                    <select id="assigned_to" name="assigned_to" class="form-control">
                        @foreach($available_doctors as $available_doctor)
                            <option value="{{$available_doctor->id}}">{{ $available_doctor->first_name }}, {{ $available_doctor->last_name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('gender'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <button type="submit" class="btn btn-primary form-control">
                        Assign
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-4" style="padding-top: 15px">
        @include('ticket/rightbar')
    </div>
@endsection
