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
            @include('_partials.sidebarv2')
        <div id="content">
            <div class="outer">
                <div class="inner bg-light lter" style="background-color: white !important;">
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
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>    <!--highCharts-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script>
        var url1=base_url+'/reports/ticket-history';
        $.getJSON(url1,
            function(data1) {
                /** Declare options after success callback. */
                console.log(data1);
                var options = {
                    chart: {
                        renderTo: 'test',
                        type: 'line'
                    },
                    title: {
                        text: 'My Tickets, for the Past 7 days'
                    },

                    subtitle: {
                        text: ''
                    },

                    yAxis: {
                        title: {
                            text: 'Number of Tickets'
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },
                    xAxis: {
                        categories: data1.dates
                    },

                    series: [{
                        name: 'Ticket Counts',
                        data: data1.counts
                    }]
                }
                var chart = new Highcharts.Chart(options);
            });

        var url2=base_url+'/reports/ticket-trend';
        $.getJSON(url2,
            function(data1) {
                /** Declare options after success callback. */
                console.log(data1);
                var options = {
                    chart: {
                        renderTo: 'ticket-trend',
                        type: 'line'
                    },
                    title: {
                        text: 'Ticket Trends'
                    },

                    subtitle: {
                        text: ''
                    },

                    yAxis: {
                        title: {
                            text: 'Number of Tickets'
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },
                    xAxis: {
                        categories: data1.dates
                    },

                    series: [{
                        name: 'Ticket Counts',
                        data: data1.values
                    }]
                }
                var chart = new Highcharts.Chart(options);
            });

        var url3=base_url+'/reports/payment-trend';
        $.getJSON(url3,
            function(data1) {
                /** Declare options after success callback. */
                console.log(data1);
                var options = {
                    chart: {
                        renderTo: 'payment-trend',
                        type: 'line'
                    },
                    title: {
                        text: 'Payment Trends'
                    },

                    subtitle: {
                        text: ''
                    },

                    yAxis: {
                        title: {
                            text: 'Amount Collected'
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },
                    xAxis: {
                        categories: data1.dates
                    },

                    series: [{
                        name: 'Amount',
                        data: data1.values
                    }]
                }
                var chart = new Highcharts.Chart(options);
            });
    </script>
    <script>
        //dataTables
        $(document).ready(function () {
            $("#dataTable").dataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        text: 'Print to PDF',
                        exportOptions: {
                            //columns: [1,2,3,4]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Print to Excel',
                        exportOptions: {
                            //columns: [1,2,3,4]
                        }
                    }
                ]
            });
            $('#c_submit').attr('disabled', true);

            //tabs
            $('.accordion-tabs-minimal').each(function(index) {
                $(this).children('li').first().children('a').addClass('is-active').next().addClass('is-open').show();
            });
            $('.accordion-tabs-minimal').on('click', 'li > a.tab-link', function(event) {
                if (!$(this).hasClass('is-active')) {
                    event.preventDefault();
                    var accordionTabs = $(this).closest('.accordion-tabs-minimal');
                    accordionTabs.find('.is-open').removeClass('is-open').hide();

                    $(this).next().toggleClass('is-open').toggle();
                    accordionTabs.find('.is-active').removeClass('is-active');
                    $(this).addClass('is-active');
                } else {
                    event.preventDefault();
                }
            });
        });
        //datePicker
        $( function() {
            $( "#yob" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd'
            });
        } );
        //inputLength
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function confirmPayment() {
            if ($('#p_check').is(":checked"))
            {
                $('#c_submit').attr('disabled', false)
            }else {
                $('#c_submit').attr('disabled', true)
            }
        }
        function printdata(DivArea){
            //save all dom structure.
            var alldocument = document.body.innerHTML;
            //get printable dom area
            var printable =  document.getElementById(DivArea).innerHTML;
            //var printable =  document.getElementById(DivArea).textContent;
            //make printable dom area occupy all page and call print function

            document.body.innerHTML = printable;


            window.print();
            //restore original dom
            document.body.innerHTML = alldocument;
        }
    </script>
    {{--<script>--}}
        {{--function reg_numberr() {--}}
            {{--var clientType = $('#type').find(":selected").text();--}}
            {{--if (clientType == 'Student'){--}}
                {{--$('.d_regNumber').append(--}}
                    {{--'<input id="reg_number" type="text" class="required form-control aki" name="reg_number" placeholder="Input Student Registration No." value="{{ old('reg_number') }}" autofocus>'--}}
                {{--)--}}
            {{--}else {--}}
                {{--$('.d_regNumber input').remove();--}}
            {{--}--}}
        {{--}--}}
    {{--</script>--}}
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
