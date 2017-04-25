<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{url('images/logo.png')}}" type="image/x-icon">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-print-1.2.4/r-2.1.1/datatables.min.css"/>
    {{--<link rel="stylesheet" href="{{url('assets/css/form-elements.css')}}">--}}
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};

        var base_url = '{!! url('/') !!}'
    </script>
</head>
<body>
    <div id="app">
        <div class="bg-dark dk" id="wrap">
            @include('_partials.header')
            @include('_partials.sidebar')
        <div id="content">
            <div class="outer">
                <div class="inner bg-light lter">
                    <div class="row" style="padding-top: 15px;">
                        @if (session('status'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Success!</strong> <br>
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Warning!</strong> <br>
                                {{ session('error') }}
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
                <!-- /.inner -->
            </div>
            <!-- /.outer -->
        </div>
            <!-- /#content -->
        </div>
        <!-- /#wrap -->
        @include('_partials.footer')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{url('assets/js/jquery.backstretch.min.js')}}"></script>

    <script src="{{url('assets/js/scripts.js')}}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-print-1.2.4/r-2.1.1/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#dataTable").dataTable();
        });
        $( function() {
            $( "#yob" ).datepicker({
                changeMonth: true,
                changeYear: true
            });
        } );
    </script>
    <script>
        function reg_numberr() {
            var clientType = $('#type').find(":selected").text();
            if (clientType == 'Student'){
                $('.d_regNumber').append(
                    '<input id="reg_number" type="text" class="required form-control aki" name="reg_number" placeholder="Input Student Registration No." value="{{ old('reg_number') }}" autofocus>'
                )
            }else {
                $('.d_regNumber input').remove();
            }
        }
    </script>
    <script>
        $( function() {
            $( "#accordion" ).accordion();
        } );
    </script>

    <!--ui kit-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.2/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.2/js/components/datepicker.min.js"></script>
</body>
</html>
