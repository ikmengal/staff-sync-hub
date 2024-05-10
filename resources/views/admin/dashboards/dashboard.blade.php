@extends('admin.layouts.app')
@push('styles')
    <link href="{{ asset('public/admin/assets/vendor/css/pages/page-profile.css') }}" rel="stylesheet" />
@endpush
@section('title', $data['title'].' | '.appName())
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">

    <div class="row mb-4">
        <div class="col-lg-9">
            <div class="card profile-card p-3">
                <div class="">
                  <div class="user-profile-header-banner position-relative rounded overflow-hidden">
                    <img src="{{ asset('public/admin') }}/assets/img/pages/curved0.jpg" alt="Banner image">
                  </div>
                  <div class="profile-image p-4 mx-4 rounded">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{{ asset('public/admin') }}/default.png" alt="user image" class="d-block h-auto rounded user-profile-img" width="80">
                        </div>
                        <div class="col">
                            <div class="h-100">
                                <h4 class="mb-1 text-capitalize">Super Admin</h4>
                                <p class="mb-0 font-weight-bold text-sm">
                                    CEO / Co-Founder
                                </p>
                            </div>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="ti ti-user-check me-1"></i>View Profile
                            </a>
                        </div>
                    </div>
                  </div>

                  <div class="profile-about mx-4 mt-4">
                    <h5>Admin , Welcome To Your Realm Of Leadership: 🏆</h5>
                    <p>Step into the future of workforce excellence, where every keystroke orchestrates seamless employee management, precise attendance tracking, salary tranquility, ticket wizardry, chat connectivity, and administrative mastery. 🚀🌟🤝   </p>
                  </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 d-lg-block d-none">
            <div class="card h-100 d-flex align-items-center justify-content-end">
                <h3 class="text-center">Have you tried <br> {{appName()}} ?</h3>
                <img src="{{ asset('public/admin') }}/assets/img/illustrations/page-misc-under-maintenance.png" alt="Dashboard Image" width="330">
            </div>
        </div>
      </div>

    <!-- Counter Boxex -->
    @include('admin.dashboards.counter-boxes')
    <!-- Counter Boxex -->

    <!-- companies slider & current month new hiring or terminations -->
    @include('admin.dashboards.companies')
    <!--/ companies slider & current month new hiring or terminations -->

    <!-- Daily attendance employee counter -->
    @include('admin.dashboards.daily-attendance-counter')
    <!--/ Daily attendance employee counter -->

    <div class="row d-none">
        <!-- Weekly employee attendance chart -->
        {{-- @include('admin.dashboards.weekly-employee-attendance-chart') --}}
        <!--/ Weekly employee attendance chart -->

        <!-- Support Tracker -->
        <div class="col-md-6 mb-4 d-none">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Support Tracker</h5>
                        <small class="text-muted">Last 7 Days</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="supportTrackerMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="supportTrackerMenu">
                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-12 col-lg-4">
                            <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-1">
                                <h1 class="mb-0">164</h1>
                                <p class="mb-0">Total Tickets</p>
                            </div>
                            <ul class="p-0 m-0">
                                <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                    <div class="badge rounded bg-label-primary p-1"><i class="ti ti-ticket ti-sm"></i></div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">New Tickets</h6>
                                        <small class="text-muted">142</small>
                                    </div>
                                </li>
                                <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                    <div class="badge rounded bg-label-info p-1">
                                        <i class="ti ti-circle-check ti-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Open Tickets</h6>
                                        <small class="text-muted">28</small>
                                    </div>
                                </li>
                                <li class="d-flex gap-3 align-items-center pb-1">
                                    <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i></div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Response Time</h6>
                                        <small class="text-muted">1 Day</small>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                            <div id="supportTracker"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Support Tracker -->
    </div>
    <div class="row d-none">
        <!-- Sales By Country -->
        <div class="col-xl-4 col-md-6 mb-4 d-none">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Sales by Countries</h5>
                        <small class="text-muted">Monthly Sales Overview</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="salesByCountry" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesByCountry">
                            <a class="dropdown-item" href="javascript:void(0);">Download</a>
                            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex align-items-center mb-4">
                            <img src="{{ asset('public/admin') }}/assets/svg/flags/us.svg" alt="User" class="rounded-circle me-3" width="34" />
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$8,567k</h6>
                                    </div>
                                    <small class="text-muted">United states</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-success fw-semibold mb-0">
                                        <i class="ti ti-chevron-up"></i>
                                        25.8%
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <img src="{{ asset('public/admin') }}/assets/svg/flags/br.svg" alt="User" class="rounded-circle me-3" width="34" />
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$2,415k</h6>
                                    </div>
                                    <small class="text-muted">Brazil</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-danger fw-semibold mb-0">
                                        <i class="ti ti-chevron-down"></i>
                                        6.2%
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <img src="{{ asset('public/admin') }}/assets/svg/flags/in.svg" alt="User" class="rounded-circle me-3" width="34" />
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$865k</h6>
                                    </div>
                                    <small class="text-muted">India</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-success fw-semibold">
                                        <i class="ti ti-chevron-up"></i>
                                        12.4%
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <img src="{{ asset('public/admin') }}/assets/svg/flags/au.svg" alt="User" class="rounded-circle me-3" width="34" />
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$745k</h6>
                                    </div>
                                    <small class="text-muted">Australia</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-danger fw-semibold mb-0">
                                        <i class="ti ti-chevron-down"></i>
                                        11.9%
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <img src="{{ asset('public/admin') }}/assets/svg/flags/fr.svg" alt="User" class="rounded-circle me-3" width="34" />
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$45</h6>
                                    </div>
                                    <small class="text-muted">France</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-success fw-semibold mb-0">
                                        <i class="ti ti-chevron-up"></i>
                                        16.2%
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="{{ asset('public/admin') }}/assets/svg/flags/cn.svg" alt="User" class="rounded-circle me-3" width="34" />
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$12k</h6>
                                    </div>
                                    <small class="text-muted">China</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-success fw-semibold mb-0">
                                        <i class="ti ti-chevron-up"></i>
                                        14.8%
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Sales By Country -->

        <!-- Total Earning -->
        <div class="col-12 col-xl-4 mb-4 col-md-6 d-none">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-1">
                    <h5 class="mb-0 card-title">Total Earning</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="totalEarning" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalEarning">
                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h1 class="mb-0 me-2">87%</h1>
                        <i class="ti ti-chevron-up text-success me-1"></i>
                        <p class="text-success mb-0">25.8%</p>
                    </div>
                    <div id="totalEarningChart"></div>
                    <div class="d-flex align-items-start my-4">
                        <div class="badge rounded bg-label-primary p-2 me-3 rounded">
                            <i class="ti ti-currency-dollar ti-sm"></i>
                        </div>
                        <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                            <div class="me-2">
                                <h6 class="mb-0">Total Sales</h6>
                                <small class="text-muted">Refund</small>
                            </div>
                            <p class="mb-0 text-success">+$98</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="badge rounded bg-label-secondary p-2 me-3 rounded">
                            <i class="ti ti-brand-paypal ti-sm"></i>
                        </div>
                        <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                            <div class="me-2">
                                <h6 class="mb-0">Total Revenue</h6>
                                <small class="text-muted">Client Payment</small>
                            </div>
                            <p class="mb-0 text-success">+$126</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Total Earning -->

        <!-- Monthly Campaign State -->
        <div class="col-xl-4 col-md-6 mb-4 d-none">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Monthly Campaign State</h5>
                        <small class="text-muted">8.52k Social Visiters</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="MonthlyCampaign" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            <a class="dropdown-item" href="javascript:void(0);">Download</a>
                            <a class="dropdown-item" href="javascript:void(0);">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-success rounded p-2"><i class="ti ti-mail ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Emails</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-semibold">12,346</p>
                                    <p class="ms-3 text-success mb-0">0.3%</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-info rounded p-2"><i class="ti ti-link ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Opened</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-semibold">8,734</p>
                                    <p class="ms-3 text-success mb-0">2.1%</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-warning rounded p-2"><i class="ti ti-click ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Clicked</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-semibold">967</p>
                                    <p class="ms-3 text-success mb-0">1.4%</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-primary rounded p-2"><i class="ti ti-users ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Subscribe</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-semibold">345</p>
                                    <p class="ms-3 text-success mb-0">8.5k</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-secondary rounded p-2">
                                <i class="ti ti-alert-triangle ti-sm text-body"></i>
                            </div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Complaints</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-semibold">10</p>
                                    <p class="ms-3 text-success mb-0">1.5%</p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-danger rounded p-2"><i class="ti ti-ban ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Unsubscribe</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-semibold">86</p>
                                    <p class="ms-3 text-success mb-0">0.8%</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Monthly Campaign State -->

        <!-- Source Visit -->
        <div class="col-xl-4 col-md-6 order-2 order-lg-1 d-none">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Source Visits</h5>
                        <small class="text-muted">38.4k Visitors</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="sourceVisits" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sourceVisits">
                            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            <a class="dropdown-item" href="javascript:void(0);">Download</a>
                            <a class="dropdown-item" href="javascript:void(0);">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 pb-1">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-label-secondary p-2 me-3 rounded">
                                    <i class="ti ti-shadow ti-sm"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Direct Source</h6>
                                        <small class="text-muted">Direct link click</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">1.2k</p>
                                        <div class="ms-3 badge bg-label-success">+4.2%</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3 pb-1">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-label-secondary p-2 me-3 rounded">
                                    <i class="ti ti-globe ti-sm"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Social Network</h6>
                                        <small class="text-muted">Social Channels</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">31.5k</p>
                                        <div class="ms-3 badge bg-label-success">+8.2%</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3 pb-1">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-label-secondary p-2 me-3 rounded">
                                    <i class="ti ti-mail ti-sm"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Email Newsletter</h6>
                                        <small class="text-muted">Mail Campaigns</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">893</p>
                                        <div class="ms-3 badge bg-label-success">+2.4%</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3 pb-1">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-label-secondary p-2 me-3 rounded">
                                    <i class="ti ti-external-link ti-sm"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Referrals</h6>
                                        <small class="text-muted">Impact Radius Visits</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">342</p>
                                        <div class="ms-3 badge bg-label-danger">-0.4%</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3 pb-1">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-label-secondary p-2 me-3 rounded">
                                    <i class="ti ti-discount-2 ti-sm"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">ADVT</h6>
                                        <small class="text-muted">Google ADVT</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">2.15k</p>
                                        <div class="ms-3 badge bg-label-success">+9.1%</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="d-flex align-items-start">
                                <div class="badge bg-label-secondary p-2 me-3 rounded">
                                    <i class="ti ti-star ti-sm"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Other</h6>
                                        <small class="text-muted">Many Sources</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">12.5k</p>
                                        <div class="ms-3 badge bg-label-success">+6.2%</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Source Visit -->

        <!-- Projects table -->
        <div class="col-12 col-xl-8 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0 d-none">
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="datatables-projects table border-top">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Name</th>
                                <th>Leader</th>
                                <th>Team</th>
                                <th class="w-px-200">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Projects table -->
    </div>
</div>
@endsection
@push('js')
<script>
    //Getting Dashboard data
    $(document).ready(function() {
        var counters = [
            'companies-count',
            'total-employees-count',
            'total-terminated-employees-count',
            'total-vehicles-count',
            'total-new-hired-count',
            'total-terminated-of-current-month-count'
        ];

        counters.forEach(function(counterName) {
            loadCounterData(counterName);
        });

        var attendanceCounters = {
            'regular-employees-count': 'regular-employees',
            'late-in-employees-count': 'late-in-employees',
            'half-day-employees-count': 'half-day-employees',
            'absent-employees-count': 'absent-employees',
        };

        $.each(attendanceCounters, function(counterKey, jsonKey) {
            loadAttedanceCounterData(counterKey, jsonKey);
        });
    });

    //fatch box counter
    function loadCounterData(counterName) {
        $.ajax({
            url: "{{ url('admin/get-counter-data') }}/" + counterName,
            type: 'GET',
            success: function(response) {
                $('#' + response.box).html(response.counter);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch data:', error);
            }
        });
    }

    //fatch attendance box counter
    function loadAttedanceCounterData(counterKey, jsonKey) {
        $.ajax({
            url: "{{ url('admin/get-attendance-counter-data') }}/" + counterKey + '/' + jsonKey,
            type: 'GET',
            success: function(response) {
                $('#' + response.box_counter).html(response.counter);
                $('#' + response.box_json).attr('data-' + response.box_json, response.json);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch data:', error);
            }
        });
    }
</script>
@endpush