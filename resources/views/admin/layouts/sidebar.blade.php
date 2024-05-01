<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">

        <a href="{{ url('/dashboard') }}" class="app-brand-link">
            @if(isset(settings()->logo) && !empty(settings()->logo))
            <img src="{{ asset('public/admin/cyberonix-logo-light.png') }}" class="img-fluid light-logo img-logo" alt="{{ settings()->name }}" />
            <img src="{{ asset('public/admin/cyberonix-logo.png') }}" class="img-fluid dark-logo img-logo" alt="{{ settings()->name }}" />
            @else
            <img src="{{ asset('public/admin/cyberonix-logo-light.png') }}" class="img-fluid light-logo img-logo" alt="Default" />
            <img src="{{ asset('public/admin/cyberonix-logo.png') }}" class="img-fluid dark-logo img-logo" alt="Default" />
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

        <!-- Layouts -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <div data-i18n="Layouts">Layouts</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="layouts-collapsed-menu.html" class="menu-link">
                        <div data-i18n="Collapsed menu">Collapsed menu</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps &amp; Pages</span>
        </li>
        <li class="menu-item {{ request()->is('admin/companies')?'active':'' }}">
            <a href="{{ route('admin.companies') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-building"></i>
                <div>All Offices</div>
            </a>
        </li>
        @canany([ 'users-list', 'roles-list', 'permissions-list', 'users-create', 'roles-create', 'permissions-create'])
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div data-i18n="Users">Users</div>
            </a>


            <ul class="menu-sub">
                @canany(['users-list','users-create'])
                <li class="menu-item">
                    <a href="{{route('users.index')}}" class="menu-link">
                        <div data-i18n="Users">Users</div>
                    </a>
                </li>
                @endcanany
                @canany(['roles-list','roles-create'])
                <li class="menu-item">
                    <a href="{{route('roles.index')}}" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                    </a>
                </li>
                @endcanany
                @canany(['permissions-list','permissions-create'])
                <li class="menu-item">
                    <a href="{{route('permissions.index')}}" class="menu-link">
                        <div data-i18n="Permissions">Permissions</div>
                    </a>
                </li>
                @endcanany
            </ul>
        </li>
        @endcanany

        <li class="menu-item {{ request()->is('admin/companies/employees')?'active':'' }}">
            <a href="{{ route('admin.companies.employees') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div>All Employees</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('admin/companies/employees/new_hiring')?'active':'' }}">
            <a href="{{ route('admin.companies.employees.new_hiring') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div>New Hired Employees</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('admin/companies/terminated_employees')?'active':'' }}">
            <a href="{{ route('admin.companies.terminated_employees') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-tag"></i>
                <div>Terminated Employees</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('admin/companies/terminated_employees_of_current_month')?'active':'' }}">
            <a href="{{ route('admin.companies.terminated_employees_of_current_month') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-tag"></i>
                <div>Terminated Employees of current month</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('admin/companies/vehicles')?'active':'' }}">
            <a href="{{ route('admin.companies.vehicles') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-car"></i>
                <div>All Vehicles</div>
            </a>
        </li>
        <li class="menu-item {{Route::is('purchase-requests.*') ? 'active' : ''}} ">
            <a href=" {{ route('purchase-requests.index') }}" class="menu-link">
                <img src="{{asset('public/svgs/prism-plus.svg')}}" alt="" class="menu-icon">
                <div>Purchase Requests</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('admin/stocks/index')?'active':'' }}">
            <a href="{{ route('receipts.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                <div>Receipts</div>
            </a>
        </li>




    </ul>
</aside>