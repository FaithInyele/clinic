<div id="left">
    <div class="media user-media bg-dark dker">
        <div class="user-media-toggleHover">
            <span class="fa fa-user"></span>
        </div>
        <div class="user-wrapper bg-dark">
            <a class="user-link" href="">
                <img class="media-object img-thumbnail user-img" alt="User Picture" style="width: 64px"
                     src="{{url('images/user.png')}}">
            </a>

            <div class="media-body">
                <h5 class="media-heading"
                    style="color: #3EBBDD">{{ Auth::user()->first_name .' , '. Auth::user()->last_name }}</h5>
                <ul class="list-unstyled user-info">
                    <li><a href="">{{ Auth::user()->role }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #menu -->
    <ul id="menu" class="bg-blue dker">

        <li class="">
            <a href="{{url('/')}}">
                <i class="fa fa-home"></i>
                <span class="link-title">Home</span>
            </a>
        </li>
        @if(Auth::user()->role == 'Admin')
            <li @if(isset($rightbar)) @if($rightbar=='user')class="active" @endif @endif>
                <a href="javascript:;">
                    <i class="fa fa-user "></i>
                    <span class="link-title">Users</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="collapse">
                    <li>
                        <a href="{{url('users/view')}}">
                            <i class="fa fa-list"></i>&nbsp; List All </a>
                    </li>
                    <li class="active">
                        <a href="{{url('users/add')}}">
                            <i class="fa fa-plus-square"></i>&nbsp; Add New User </a>
                    </li>
                </ul>
            </li>
        @endif
        <li @if(isset($rightbar)) @if($rightbar=='client')class="active" @endif @endif>
            <a href="javascript:;">
                <i class="fa fa-user "></i>
                <span class="link-title">Clients</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="collapse">
                @if(Auth::user()->role == 'Receptionist' || Auth::user()->role == 'Admin')
                    <li>
                        <a href="{{url('clients')}}">
                            <i class="fa fa-list"></i>&nbsp; List All </a>
                    </li>
                @endif
                @if(Auth::user()->role == 'Receptionist' || Auth::user()->role == 'Admin')
                    <li>
                        <a href="{{url('clients/add')}}">
                            <i class="fa fa-plus-square"></i>&nbsp; Add New Client </a>
                    </li>
                @endif
            </ul>
        </li>
        <li @if(isset($rightbar)) @if($rightbar=='ticket')class="active" @endif @endif>
            <a href="javascript:;">
                <i class="fa fa-id-badge "></i>
                <span class="link-title">Tickets</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="collapse">
                @if(Auth::user()->role == 'Admin')
                    <li>
                        <a href="{{url('tickets')}}">
                            <i class="fa fa-list"></i>&nbsp; List All</a>
                    </li>
                @endif
                @if(Auth::user()->role != 'Admin')
                    <li>
                        <a href="{{url('tickets/my-tickets')}}">
                            <i class="fa fa-list"></i>&nbsp; My Tickets (All)</a>
                    </li>
                @endif
                @if(Auth::user()->role == 'Nurse/Doctor' || Auth::user()->role == 'Doctor' || Auth::user()->role == 'Admin' )
                    {{--<li>--}}
                        {{--<a href="{{url('tickets/consultations')}}">--}}
                            {{--<i class="fa fa-list"></i>&nbsp; Requested Consultations</a>--}}
                    {{--</li>--}}
                @endif
                @if(Auth::user()->role == 'Receptionist' || Auth::user()->role == 'Admin')
                    <li>
                        <a href="{{url('tickets/add')}}">
                            <i class="fa fa-plus-square"></i>&nbsp; Create New Ticket </a>
                    </li>
                    <li>
                        <a href="{{url('tickets/payments')}}">
                            <i class="fa fa-plus-square"></i>&nbsp; Payments </a>
                    </li>
                @endif
            </ul>
        </li>
        <li @if(isset($rightbar)) @if($rightbar=='resources')class="active" @endif @endif>
            <a href="javascript:;">
                <i class="fa fa-bars "></i>
                <span class="link-title">Resources</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="collapse">
                @if(Auth::user()->role == 'Chemist' || Auth::user()->role == 'Admin')
                    <li>
                        <a href="{{url('resources/chemist')}}">
                            <i class="fa fa-medkit"></i> Chemist Resources</a>
                    </li>
                @endif
                @if(Auth::user()->role == 'Lab Technician' || Auth::user()->role == 'Admin')
                    <li>
                        <a href="{{url('resources/lab')}}">
                            <i class="fa fa-thermometer"></i>&nbsp; Lab Resources</a>
                    </li>
                @endif
                <li>
                    <a href="{{url('resources/nurse-station')}}">
                        <i class="fa fa-stethoscope"></i>&nbsp; Nurse Station</a>
                </li>
            </ul>
        </li>
        <li @if(isset($rightbar)) @if($rightbar=='preferences')class="active" @endif @endif>
            <a href="javascript:;">
                <i class="fa fa-cog "></i>
                <span class="link-title">Preferences</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="collapse">
                @if(Auth::user()->role == 'Admin')
                    <li>
                        <a href="{{url('preferences')}}">
                            <i class="fa fa-sliders"></i> Preferences</a>
                    </li>
                    <li>
                        <a href="{{url('preferences/service-fees')}}">
                            <i class="fa fa-money"></i>&nbsp; Service Fees</a>
                    </li>
                @endif
            </ul>
        </li>
        <li @if(isset($rightbar)) @if($rightbar=='payments')class="active" @endif @endif>
            <a href="javascript:;">
                <i class="fa fa-money "></i>
                <span class="link-title">Payments</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="collapse">
                @if(Auth::user()->role == 'Admin')
                    <li>
                        <a href="{{url('payments')}}">
                            <i class="fa fa-money"></i> Show All</a>
                    </li>
                @endif
            </ul>
        </li>
    </ul>
    <!-- /#menu -->
</div>
<!-- /#left -->