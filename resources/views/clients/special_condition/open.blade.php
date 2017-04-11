@extends('layouts.app')

@section('content')
    <div class="col-lg-8">

        <form class="uk-form" role="form" method="POST" action="{{url('clients/medical-conditions/open')}}">
        {{ csrf_field() }}

        <!--Client_id-->
            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                <label for="first_name" class="col-md-4 control-label">Client Name[First]</label><br>
                <input type="hidden" name="client_id" value="{{$client_id}}">

                <div class="row">
                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ $client_name }}" required disabled>

                    @if ($errors->has('first_name'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <!--description-->
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description" class="col-md-4 control-label">Description</label><br>

                <div class="row">
                    <textarea name="description" id="description" class="form-control" autofocus>

                    </textarea>

                    @if ($errors->has('description'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <button type="submit" class="btn btn-primary form-control">
                        Add
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-4" style="padding-top: 15px">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Warning!</strong> <br>
            1. Ensure NO fields are left Blank.<br>
            3. Ensure All data is Valid.
        </div>
        <!-- .well well-small -->
        <div class="well well-small dark">
            <strong>Stats</strong> <br>
            <ul class="list-unstyled">
                <li>My Facility <span class="inlinesparkline pull-right">Egerton, Sanatorium</span></li>
                <hr>
                <li>Active Users <span class="dynamicbar pull-right">20</span></li>
                <li>Inactive Users <span class="inlinebar pull-right">4</span></li>
                <hr>
                <li>Total Users <span class="dynamicsparkline pull-right">24</span></li>
            </ul>
        </div>
        <!-- /.well well-small -->
        <!-- .well well-small -->
        <div class="well well-small dark">

        </div>
    </div>
@endsection
