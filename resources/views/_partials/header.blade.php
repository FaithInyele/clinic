<div id="top">
    <template id="vuenav">
        <!-- .navbar -->
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container-fluid">


                <!-- Brand and toggle get grouped for better mobile display -->
                <header class="navbar-header">

                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="" class="navbar-brand">
                        <img src="{{url('images/logo.png')}}" style="max-height: 50px;width: auto">
                    </a>

                </header>



                <div class="topnav">
                    <div class="btn-group">
                        <a data-placement="bottom" data-original-title="Fullscreen" data-toggle="tooltip"
                           class="btn btn-default btn-sm" id="toggleFullScreen">
                            <i class="glyphicon glyphicon-fullscreen"></i>
                        </a>
                    </div>
                    <div class="btn-group">
                        {{--<a data-placement="bottom" data-original-title="Pending" data-toggle="tooltip"
                           class="btn btn-default btn-sm">
                            <i class="fa fa-envelope"></i>
                            <span class="label label-warning">5</span>
                        </a>
                        <a data-placement="bottom" data-original-title="Finished Today" href="#" data-toggle="tooltip"
                           class="btn btn-default btn-sm">
                            <i class="fa fa-comments"></i>
                            <span class="label label-danger">4</span>
                        </a>
                        <a data-toggle="modal" data-original-title="Help" data-placement="bottom"
                           class="btn btn-default btn-sm"
                           href="#helpModal">
                            <i class="fa fa-question"></i>
                        </a>--}}
                    </div>
                    <div class="btn-group">
                        <a href="{{ url('/logout') }}" data-toggle="tooltip" data-original-title="Logout" data-placement="bottom"
                           class="btn btn-metis-1 btn-sm"
                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();" style="background-color: red;color: white;font-weight: 700">
                            <i class="fa fa-power-off btn-logout">Log Out</i>
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <div class="btn-group">
                        <a data-placement="bottom" data-original-title="Show / Hide Left" data-toggle="tooltip"
                           class="btn btn-primary btn-sm toggle-left" id="menu-toggle">
                            <i class="fa fa-bars"></i>
                        </a>

                    </div>

                </div>




                <div class="collapse navbar-collapse navbar-ex1-collapse">

                    <!-- .nav -->
                    <ul class="nav navbar-nav">
                        <li><a href="{{url('/')}}"><i class="fa fa-home"> Home</i></a></li>
                        {{--<li><a href=""><i class="fa fa-user-md"> My Profile</i></a></li>--}}
                    </ul>
                    <!-- /.nav -->
                </div>
            </div>
            <!-- /.container-fluid -->
        </nav>
        <!-- /.navbar -->
    </template>

{{--    <header class="head">
        <div class="search-bar">
            --}}{{--<form class="main-search" action="">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Live Search ...">
                    <span class="input-group-btn">
                                                <button class="btn btn-primary btn-sm text-muted" type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                </div>
            </form>--}}{{--
            <!-- /.main-search -->                                </div>
        <!-- /.search-bar -->
        <div class="main-bar">
            <h3 style="color: #149ED2">
                {{ $title }}
            </h3>
        </div>
        <!-- /.main-bar -->
    </header>--}}
    <!-- /.head -->
</div>
<!-- /#top -->