<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ Auth::user()->image }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <a><i class="fa fa-circle text-success"></i></a>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview {{ Request::is('dashboard')? 'active' : '' }}">
                <a href="{{ route('user-dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview {{ Request::is('user/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Users</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('user/register')? 'active' : '' }}"><a href="{{ route('user-register-view') }}"><i class="fa fa-circle-o"></i> Registration</a></li>
                    <li><a href="index2.html"><i class="fa fa-circle-o"></i> List</a></li>
                    <li><a href="#"><i class="fa fa-circle-o {{ Request::is('profile/*')? 'active' : '' }}"></i> My profile</a></li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('account/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Accouts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('account-register-view')}}"><i class="fa fa-circle-o"></i> Registration</a></li>
                    <li><a href="index2.html"><i class="fa fa-circle-o"></i> List</a></li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('owner/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-briefcase"></i>
                    <span>Owners</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('owner-register-view')}}"><i class="fa fa-circle-o"></i> Registration</a></li>
                    <li><a href="index2.html"><i class="fa fa-circle-o"></i> List</a></li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('hr/*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-male"></i> <span>Staff & Labour</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('hr/staff/*')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-pencil"></i> Staff
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{route('staff-register-view')}}"><i class="fa fa-circle-o"></i> Registration</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> List</a></li>
                        </ul>
                    </li>
                    <li class="{{ Request::is('hr/labour')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-hand-paper-o"></i> Labour
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{route('labour-register-view')}}"><i class="fa fa-circle-o"></i> Registration</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> List</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('product/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-dollar"></i>
                    <span>Products</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('product-register-view')}}"><i class="fa fa-circle-o"></i> Registration</a></li>
                    <li><a href="index2.html"><i class="fa fa-circle-o"></i> List</a></li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('machine/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Machines</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('machine/excavator/*')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-arrow-down"></i> Excavators
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{route('excavator-register-view')}}"><i class="fa fa-circle-o"></i> Registration</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> List</a></li>
                        </ul>
                    </li>
                    <li class="{{ Request::is('machine/jackhammer')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-thumb-tack"></i> Jackhammers
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{route('jackhammer-register-view')}}"><i class="fa fa-circle-o"></i> Registration</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> List</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('vehicle/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-truck"></i>
                    <span>Vehicles</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('vehicle-register-view') }}"><i class="fa fa-circle-o"></i> Registration</a></li>
                    <li><a href="index2.html"><i class="fa fa-circle-o"></i> List</a></li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('vehicletype/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-bus"></i> <span>Vehicle Type</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('vehicle-type-register-view') }}"><i class="fa fa-circle-o"></i> Registration</a></li>
                    <li><a href="index2.html"><i class="fa fa-circle-o"></i> List</a></li>
                </ul>
            </li>
        </ul>
    </section>
<!-- /.sidebar -->
</aside>