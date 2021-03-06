<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ $currentUser->image }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $currentUser->name }}</p>
                <a href="{{ Request::is('my/profile') ? '#' : route('user-profile-view') }}"><i class="fa fa-hand-o-right"></i> View Profile</a>
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
            @if($currentUser->role == 'superadmin')
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
                            <a href="{{ route('user-list') }}">
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
                        <a href="{{ route('owner-list') }}">
                            <i class="fa fa-circle-o"></i> List
                        </a>
                    </li>
                </ul>
            </li>
             <li class="treeview {{ Request::is('product/*')? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-industry"></i>
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
                        <li class="{{ Request::is('product/list/superadmin')? 'active' : '' }}">
                            <a href="{{route('product-list-superadmin') }}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if($currentUser->role == 'admin' || $currentUser->role == 'user')
                <li class="treeview {{ Request::is('sales/*')? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-arrow-up"></i>
                        <span>Sales</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('sales/register')? 'active' : '' }}">
                            <a href="{{route('sales-register-view')}}">
                                <i class="fa fa-circle-o"></i> Registration
                            </a>
                        </li>
                        <li class="{{ Request::is('sales/list')? 'active' : '' }}">
                            <a href="{{route('sales-list-search')}}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                        <li class="{{ Request::is('sales/weighment/*')? 'active' : '' }}">
                            <a href="{{route('sales-weighment-pending-view')}}">
                                <i class="fa fa-circle-o"></i> Weighment Registration
                            </a>
                        </li>
                        <li class="{{ Request::is('sales/multiple/*')? 'active' : '' }}">
                            <a href="{{route('multiple-sales-register-view')}}">
                                <i class="fa fa-circle-o"></i> Multiple Sale Registration
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {{ Request::is('purchases/*')? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-arrow-down"></i>
                        <span>Purchase & Expense</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('purchases/register')? 'active' : '' }}">
                            <a href="{{route('purchases-register-view')}}">
                                <i class="fa fa-circle-o"></i> Registration
                            </a>
                        </li>
                        <li class="{{ Request::is('purchases/list')? 'active' : '' }}">
                            <a href="{{route('purchases-list-search')}}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {{ Request::is('daily-statement/*')? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-calendar-plus-o"></i>
                        <span>Daily Resources</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('daily-statement/register')? 'active' : '' }}">
                            <a href="{{route('daily-statement-register-view')}}">
                                <i class="fa fa-circle-o"></i> Registration
                            </a>
                        </li>
                        <li class="{{ Request::is('daily-statement/list/*')? 'active' : '' }}">
                            <a href="{{route('daily-statement-list-employee')}}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {{ Request::is('monthly-statement/*')? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-calendar"></i>
                        <span>Monthly Resources</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('monthly-statement/register')? 'active' : '' }}">
                            <a href="{{ route('monthly-statement-register-view') }}">
                                <i class="fa fa-circle-o"></i> Register
                            </a>
                        </li>
                        <li class="{{ Request::is('monthly-statement/list/*')? 'active' : '' }}">
                            <a href="{{route('monthly-statement-list-employee')}}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {{ Request::is('voucher/*')? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-tags"></i>
                        <span>Vouchers</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('voucher/register')? 'active' : '' }}">
                            <a href="{{route('voucher-register-view')}}">
                                <i class="fa fa-circle-o"></i> Register
                            </a>
                        </li>
                        <li class="{{ Request::is('voucher/list/*')? 'active' : '' }}">
                            <a href="{{route('cash-voucher-list')}}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {{ (Request::is('statement/*'))? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-dollar"></i>
                        <span>Statements</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('statement/account-statement')? 'active' : '' }}">
                            <a href="{{route('account-statement-list-search')}}">
                                <i class="fa fa-circle-o"></i> Account Statement
                            </a>
                        </li>
                        <li class="{{ Request::is('statement/daily-statement')? 'active' : '' }}">
                            <a href="{{route('daily-statement-list-search')}}">
                                <i class="fa fa-circle-o"></i> Transaction Statement
                            </a>
                        </li>
                        <li class="{{ Request::is('statement/sale')? 'active' : '' }}">
                            <a href="{{route('sale-statement-list-search')}}">
                                <i class="fa fa-circle-o"></i> Sales Statement
                            </a>
                        </li>
                        <li class="{{ Request::is('statement/credit-list')? 'active' : '' }}">
                            <a href="{{route('credit-list')}}">
                                <i class="fa fa-circle-o"></i> Credit List
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
                            <a href="{{route('account-list')}}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {{ Request::is('hr/employee/*') ? 'active' : '' }}">
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
                            <a href="{{route('employee-list')}}">
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
                                <li class="{{ Request::is('machine/excavator/list')? 'active' : '' }}">
                                    <a href="{{ route('excavator-list') }}">
                                        <i class="fa fa-circle-o"></i> List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('machine/jackhammer/*')? 'active' : '' }}">
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
                                    <a href="{{route('jackhammer-list') }}">
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
                        <span>Trucks</span>
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
                            <a href="{{route('vehicle-list') }}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {{ Request::is('vehicle-type/*')? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i> <span>Truck Type & Royalty</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        @if($currentUser->role == 'admin')
                            <li class="{{ Request::is('vehicle-type/register')? 'active' : '' }}">
                                <a href="{{ route('vehicle-type-register-view') }}">
                                    <i class="fa fa-circle-o"></i> Registration
                                </a>
                            </li>
                        @endif
                        <li class="{{ Request::is('vehicle-type/chart')? 'active' : '' }}">
                            <a href="{{route('vehicle-type-chart') }}">
                                <i class="fa fa-circle-o"></i> Royalty Chart
                            </a>
                        </li>
                        <li class="{{ Request::is('vehicle-type/list')? 'active' : '' }}">
                            <a href="{{route('vehicle-type-list') }}">
                                <i class="fa fa-circle-o"></i> Royalty List
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if($currentUser->role == 'admin')
                <li class="treeview {{ Request::is('product/*')? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-industry"></i>
                        <span>Products</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('product/list')? 'active' : '' }}">
                            <a href="{{route('product-list') }}">
                                <i class="fa fa-circle-o"></i> List
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </section>
<!-- /.sidebar -->
</aside>