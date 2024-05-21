<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">

        <a href="{{ url('/dashboard') }}" class="app-brand-link">
            @if(isset(settings()->logo) && !empty(settings()->logo))
            <img src="{{ asset('public/admin/cyberonix-logo-light.png') }}" class="img-fluid light-logo img-logo" alt="{{ settings()->name }}" />
            {{-- <img src="{{ asset('public/admin/cyberonix-logo.png') }}" class="img-fluid dark-logo img-logo" alt="{{ settings()->name }}" /> --}}
            @else
            <img src="{{ asset('public/admin/cyberonix-logo-light.png') }}" class="img-fluid light-logo img-logo" alt="Default" />
            {{-- <img src="{{ asset('public/admin/cyberonix-logo.png') }}" class="img-fluid dark-logo img-logo" alt="Default" /> --}}
            @endif
        </a>


        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>

    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        @can('dashboards-list')
        <li class="menu-item {{ request()->is('dashboard')?'active':'' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboards">Dashboard</div>
            </a>
        </li>
        @endcan

        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps &amp; Pages</span>
        </li>
        <li class="menu-item {{ request()->is('admin/companies')?'active':'' }}">
            @can('offices-list')
            <a href="{{ route('admin.companies') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-building"></i>
                <div>All Offices</div>
            </a>
            @endcan
        </li>
        <li class="menu-item {{ request()->is('admin/salary-reports')?'active':'' }}">
            @can('salary-reports-list')
            <a href="{{ route('admin.salary-reports') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-wallet"></i>
                <div>Salary Reports</div>
            </a>
            @endcan
        </li>
        @canany([ 'users-list', 'roles-list', 'permissions-list', 'users-create', 'roles-create', 'permissions-create'])
        <li class="menu-item {{ Route::is('users.*') ||
                Route::is('permissions.*') ||
                Route::is('roles.*') 
                ? 'open active'
            : '' }}"">
            <a href=" javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-user"></i>
            <div data-i18n="Users">Users</div>
            </a>


            <ul class="menu-sub ">
                @canany(['users-list','users-create'])
                <li class="menu-item {{Route::is('users.*') ? 'active' : ''}}">
                    <a href="{{route('users.index')}}" class="menu-link">
                        <div data-i18n="Users">Users</div>
                    </a>
                </li>
                @endcanany
                @canany(['roles-list','roles-create'])
                <li class="menu-item {{Route::is('roles.*') ? 'active' : ''}}">
                    <a href="{{route('roles.index')}}" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                    </a>
                </li>
                @endcanany
                @canany(['permissions-list','permissions-create'])
                <li class="menu-item {{Route::is('permissions.*') ? 'active' : ''}}">
                    <a href="{{route('permissions.index')}}" class="menu-link">
                        <div data-i18n="Permissions">Permissions</div>
                    </a>
                </li>
                @endcanany
            </ul>
        </li>
        @endcanany
        @canany(['employees-list', 'employees-new-hired-employee', 'employees-terminated', 'employees-terminated','employees-terminated-current-month'])
        <li class="menu-item  {{ Route::is('admin.companies.employees') || Route::is('admin.companies.employees.new_hiring')  ||  Route::is('admin.companies.terminated_employees') || Route::is('admin.companies.terminated_employees_of_current_month') ? 'open active' : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Employees">Employees</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/companies/employees')?'active':'' }}">
                    @can('employees-list')
                    <a href="{{ route('admin.companies.employees') }}" class="menu-link">

                        <div>All Employees</div>
                    </a>
                    @endcan
                </li>
                <li class="menu-item {{ request()->is('admin/companies/employees/new_hiring')?'active':'' }}">
                    @can('employees-new-hired-employee')
                    <a href="{{ route('admin.companies.employees.new_hiring') }}" class="menu-link">

                        <div>New Hired Employees</div>
                    </a>
                    @endcan
                </li>
                <li class="menu-item {{ request()->is('admin/companies/terminated_employees')?'active':'' }}">
                    @can('employees-terminated')
                    <a href="{{ route('admin.companies.terminated_employees') }}" class="menu-link">
                        <div>Terminated Employees</div>
                    </a>
                    @endcan
                </li>
                <li class="menu-item {{ request()->is('admin/companies/terminated_employees_of_current_month')?'active':'' }}">
                    @can('employees-terminated-current-month')
                    <a href="{{ route('admin.companies.terminated_employees_of_current_month') }}" class="menu-link">

                        <div>Terminated Employees of current month</div>
                    </a>
                    @endcan
                </li>


            </ul>

        </li>
        @endcanany


        @can('grievances-list')
        <li class="menu-item {{ request()->is('grievances')?'active':'' }}">
            <a href="{{ route('grievances.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-file-dislike"></i>
                <div>Grievances</div>
            </a>
        </li>
        @endcan
        @can('ip-addresses-list')
        <li class="menu-item {{ request()->is('ip-addresses')?'active':'' }}">
            <a href="{{ route('ip-addresses.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-location "></i>
                <div>IP Addresses</div>
            </a>
        </li>
        @endcan
        <li class="menu-item {{ request()->is('user_leaves')?'active':'' }}">
            @can('user-leaves-list')
            <a href="{{ route('user_leaves.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-clock"></i>
                <div>All Leaves</div>
            </a>
            @endcan
        </li>
        @can('employee-letters-list')
        <li class="menu-item {{ request()->is('employee-letters')?'active':'' }}">
            <a href="{{ route('employee-letters.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-file-certificate"></i>
                <div>Employee Letters</div>
            </a>
        </li>
        @endcan
        @can('vahicles-list')
        <li class="menu-item {{ request()->is('admin/companies/vehicles')?'active':'' }}">
            <a href="{{ route('admin.companies.vehicles') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-car"></i>
                <div>Vehicles</div>
            </a>
        </li>
        @endcan
        <!-- purchases -->
        @canany(['purchase-requests-list', 'purchase-requests-list', 'estimates-list', 'receipts-list'])
        <li class="menu-item {{ Route::is('purchase-requests.*') ||
                Route::is('estimates.*') ||
                Route::is('receipts.*') 
                        ? 'open active'
                    : '' }}">
            @can('purchase-requests-list')
            <a href=" javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <div data-i18n="Purchases">Purchases</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item hover is_shown {{Route::is('purchase-requests.*') ? 'active' : ''}} ">
                    @can('purchase-requests-list')
                    <a href="{{ route('purchase-requests.index') }}" class="menu-link">
                        <div data-i18n="Requests">Requests</div>
                    </a>
                    @endcan
                </li>
                <li class="menu-item hover is_shown {{Route::is('estimates.*') ? 'active' : ''}} ">
                    @can('estimates-list')
                    <a href="{{ route('estimates.index') }}" class="menu-link">
                        <div data-i18n="Estimates">Estimates</div>
                    </a>
                    @endcan
                </li>
                <li class="menu-item hover is_shown {{Route::is('receipts.*') ? 'active' : ''}} ">
                    @can('receipts-list')
                    <a href="{{ route('receipts.index') }}" class="menu-link">
                        <div data-i18n="Receipts">Receipts</div>
                    </a>
                    @endcan

                </li>
            </ul>
            @endcan
        </li>
        @endcanany
        {{-- @canany(['pre-employees-list']) --}}
        <li class="menu-item {{ Route::is('pre-employees.*')  
                ? 'open active'
            : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div data-i18n="Pre Employees">Pre Employees</div>
            </a>
            <ul class="menu-sub">
                {{-- @can('pre-employees-list') --}}
                <li class="menu-item {{ Route::is('pre-employees.*') ? 'active' : '' }}">
                    <a href="{{ route('pre-employees.index') }}" class="menu-link">

                        <div>Pre Employees</div>
                    </a>
                </li>
                {{-- @endcan --}}



            </ul>

        </li>
        {{-- @endcanany --}}
        @canany(['salaries-list'])
        <li class="menu-item {{ Route::is('salaries.*')  
                ? 'open active'
            : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-wallet"></i>
                <div data-i18n="Salary">Salary</div>
            </a>
            <ul class="menu-sub">
                @can('salaries-list')
                <li class="menu-item {{ Route::is('salaries.*') ? 'active' : '' }}">
                    <a href="{{ route('salaries.detail') }}" class="menu-link">

                        <div>Salary Detail</div>
                    </a>
                </li>
                @endcan



            </ul>

        </li>
        @endcanany

        @canany(['attendances-list'])
        <li class="menu-item {{ request()->is('admin/company/attendance')  ? 'open active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-calendar"></i>
                <div data-i18n="Attendance">Attendance</div>
            </a>
            <ul class="menu-sub">
                @can('attendances-list')
                <li class="menu-item {{ request()->is('admin/company/attendance') ? 'active' :'' }}">
                    <a href="{{ route('admin.companies.attendance') }}" class="menu-link">

                        <div>Attendance List</div>
                    </a>
                </li>
                @endcan


            </ul>

        </li>
        @endcanany


        <!-- purchases -->
    </ul>
</aside>