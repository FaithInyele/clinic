<div id="left">
    <div class="media user-media bg-dark dker">
        <div class="user-media-toggleHover">
            <span class="fa fa-user"></span>
        </div>
        <div class="user-wrapper bg-dark">
            <a class="user-link" href="">
                <img class="media-object img-thumbnail user-img" alt="User Picture" style="width: 64px" src="{{url('images/user.png')}}">
                <span class="label label-danger user-label">16</span>
            </a>

            <div class="media-body">
                <h5 class="media-heading" style="color: #3EBBDD">{{ Auth::user()->first_name .' , '. Auth::user()->last_name }}</h5>
                <ul class="list-unstyled user-info">
                    <li><a href="">{{ Auth::user()->role }}</a></li>
                    <li>Last Access : <br>
                        <small><i class="fa fa-calendar"></i>&nbsp;16 Mar 16:32</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #menu -->
    <ul id="menu" class="bg-blue dker">

        <li class="">
            <a href="{{url('/')}}">
                <i class="fa fa-home"></i><span class="link-title">&nbsp;Homee</span>
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
                <li>
                    <a href="{{url('clients')}}">
                        <i class="fa fa-list"></i>&nbsp; List All </a>
                </li>
                @if(Auth::user()->role == 'Receptionist')
                    <li>
                        <a href="{{url('clients/add')}}">
                            <i class="fa fa-plus-square"></i>&nbsp; Add New Client </a>
                    </li>
                @endif
            </ul>
        </li>
        <li @if(isset($rightbar)) @if($rightbar=='ticket')class="active" @endif @endif>
            <a href="javascript:;">
                <i class="fa fa-user "></i>
                <span class="link-title">Tickets</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="collapse">
                <li>
                    <a href="{{url('tickets')}}">
                        <i class="fa fa-list"></i>&nbsp; List All</a>
                </li>
                <li>
                    <a href="{{url('tickets/my-tickets')}}">
                        <i class="fa fa-list"></i>&nbsp; My Tickets (All)</a>
                </li>
                <li>
                    <a href="{{url('tickets/add')}}">
                        <i class="fa fa-plus-square"></i>&nbsp; Create New Ticket </a>
                </li>
                <li>
                    <a href="{{url('tickets/payments')}}">
                        <i class="fa fa-plus-square"></i>&nbsp; Payments </a>
                </li>
            </ul>
        </li>
        <li @if(isset($rightbar)) @if($rightbar=='resources')class="active" @endif @endif>
            <a href="javascript:;">
                <i class="fa fa-user "></i>
                <span class="link-title">Resources</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="collapse">
                <li>
                    <a href="{{url('resources/chemist')}}">
                        <i class="fa fa-list"></i> Chemist Resources</a>
                </li>
                <li>
                    <a href="{{url('resources/lab')}}">
                        <i class="fa fa-list"></i>&nbsp; Lab Resources</a>
                </li>
                <li>
                    <a href="{{url('resources/nurse-station')}}">
                        <i class="fa fa-list"></i>&nbsp; Nurse Station</a>
                </li>
            </ul>
        </li>
        <li @if(isset($rightbar)) @if($rightbar=='preferences')class="active" @endif @endif>
            <a href="javascript:;">
                <i class="fa fa-user "></i>
                <span class="link-title">Preferences</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="collapse">
                <li>
                    <a href="{{url('preferences')}}">
                        <i class="fa fa-list"></i> Preferences</a>
                </li>
                <li>
                    <a href="{{url('preferences/service-fees')}}">
                        <i class="fa fa-list"></i>&nbsp; Service Fees</a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- /#menu -->
</div>
<!-- /#left -->