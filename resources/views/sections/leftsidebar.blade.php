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
                <a><i class="fa fa-circle text-success"></i>Online</a>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview {{ Request::is('dashboard')? 'active' : '' }}">
                <a href="{{ route('user-dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview {{ Request::is('user/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Users</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('user/register')? 'active' : '' }}">
                        <a href="{{ route('user-register-view') }}">
                            <i class="fa fa-circle-o"></i> Registration
                        </a>
                    </li>
                    <li class="{{ Request::is('user/list')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> List
                        </a>
                    </li>
                    <li class="{{ Request::is('user/profile')? 'active' : '' }}">
                        <a href="{{ route('user-profile-view') }}">
                            <i class="fa fa-circle-o"></i> My profile
                        </a>
                    </li>
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
                    <li class="{{ Request::is('account/register')? 'active' : '' }}">
                        <a href="{{route('account-register-view')}}">
                            <i class="fa fa-circle-o"></i> Registration
                        </a>
                    </li>
                    <li class="{{ Request::is('account/list')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> List
                        </a>
                    </li>
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
                    <li class="{{ Request::is('owner/register')? 'active' : '' }}">
                        <a href="{{route('owner-register-view')}}">
                            <i class="fa fa-circle-o"></i> Registration
                        </a>
                    </li>
                    <li class="{{ Request::is('owner/list')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('hr/employee') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-male"></i>
                    <span>Employees</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('hr/employee/register')? 'active' : '' }}">
                        <a href="{{route('employee-register-view')}}">
                            <i class="fa fa-circle-o"></i> Registration
                        </a>
                    </li>
                    <li class="{{ Request::is('hr/employee/list')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> List
                        </a>
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
                    <li class="{{ Request::is('product/register')? 'active' : '' }}">
                        <a href="{{route('product-register-view')}}">
                            <i class="fa fa-circle-o"></i> Registration
                        </a>
                    </li>
                    <li class="{{ Request::is('product/list')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> List
                        </a>
                    </li>
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
                            <li class="{{ Request::is('machine/excavator/register')? 'active' : '' }}">
                                <a href="{{route('excavator-register-view')}}">
                                    <i class="fa fa-circle-o"></i> Registration
                                </a>
                            </li>
                            <li class="{{ Request::is('machine/excavator/register')? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> List
                                </a>
                            </li>
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
                            <li class="{{ Request::is('machine/jackhammer/register')? 'active' : '' }}">
                                <a href="{{route('jackhammer-register-view')}}">
                                    <i class="fa fa-circle-o"></i> Registration
                                </a>
                            </li>
                            <li class="{{ Request::is('machine/jackhammer/list')? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> List
                                </a>
                            </li>
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
                    <li class="{{ Request::is('vehicle/register')? 'active' : '' }}">
                        <a href="{{ route('vehicle-register-view') }}">
                            <i class="fa fa-circle-o"></i> Registration
                        </a>
                    </li>
                    <li class="{{ Request::is('vehicle/list')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> List
                        </a>
                    </li>
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
                    <li class="{{ Request::is('vehicletype/register')? 'active' : '' }}">
                        <a href="{{ route('vehicle-type-register-view') }}">
                            <i class="fa fa-circle-o"></i> Registration
                        </a>
                    </li>
                    <li class="{{ Request::is('vehicletype/list')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> List
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
<!-- /.sidebar -->
</aside>