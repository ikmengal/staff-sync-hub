<div class="row">
    <!-- Slider -->
    <div class="col-lg-6 mb-4">
        <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg" id="swiper-with-pagination-cards" >
            <div class="swiper-wrapper">
                {{-- <div class="swiper-slide">
                    <div class="row">
                        <h3 style="text-align: center; height:200px">loading...</h3>
                    </div>
                </div> --}}
                @foreach (getAllCompanies() as $company)
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-white mb-0 mt-2">{{ $company->name }}</h5>
                            </div>
                            <div class="row">
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                    <h6 class="text-white mt-0 mt-md-3 mb-3">Company Info</h6>
                                    <div class="row">
                                        <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ count($company->total_employees) }}</p>
                                            <p class="mb-0">Active Employees</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ count($company->vehicles) }}</p>
                                            <p class="mb-0">Vehicles</p>
                                            </li>
                                        </ul>
                                        </div>
                                        <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ count($company->total_terminated_employees) }}</p>
                                            <p class="mb-0">Terminated Employees</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ $company->vehicle_percent }}%</p>
                                            <p class="mb-0">Vehhicles Percent</p>
                                            </li>
                                        </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                    @if(!empty($company->favicon))
                                        <img
                                            src="{{ $company->base_url }}/public/admin/assets/img/favicon/{{ $company->favicon }}"
                                            alt="{{ $company->name }}"
                                            width="170"
                                            class="card-website-analytics-img"
                                        />
                                    @else
                                        <img
                                            src="{{ asset('public/admin/default.png') }}"
                                            alt="Default Logo"
                                            width="170"
                                            class="card-website-analytics-img"
                                        />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <!-- Slider -->

    <!-- Sales Overview -->
    {{-- <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header">
            <div class="d-flex justify-content-between">
                <small class="d-block mb-1 text-muted">Sales Overview</small>
                <p class="card-text text-success">+18.2%</p>
            </div>
            <h4 class="card-title mb-1">$42.5k</h4>
            </div>
            <div class="card-body">
            <div class="row">
                <div class="col-4">
                <div class="d-flex gap-2 align-items-center mb-2">
                    <span class="badge bg-label-info p-1 rounded"
                    ><i class="ti ti-shopping-cart ti-xs"></i
                    ></span>
                    <p class="mb-0">Order</p>
                </div>
                <h5 class="mb-0 pt-1 text-nowrap">62.2%</h5>
                <small class="text-muted">6,440</small>
                </div>
                <div class="col-4">
                <div class="divider divider-vertical">
                    <div class="divider-text">
                    <span class="badge-divider-bg bg-label-secondary">VS</span>
                    </div>
                </div>
                </div>
                <div class="col-4 text-end">
                <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                    <p class="mb-0">Visits</p>
                    <span class="badge bg-label-primary p-1 rounded"><i class="ti ti-link ti-xs"></i></span>
                </div>
                <h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">25.5%</h5>
                <small class="text-muted">12,749</small>
                </div>
            </div>
            <div class="d-flex align-items-center mt-4">
                <div class="progress w-100" style="height: 8px">
                <div
                    class="progress-bar bg-info"
                    style="width: 70%"
                    role="progressbar"
                    aria-valuenow="70"
                    aria-valuemin="0"
                    aria-valuemax="100"
                ></div>
                <div
                    class="progress-bar bg-primary"
                    role="progressbar"
                    style="width: 30%"
                    aria-valuenow="30"
                    aria-valuemin="0"
                    aria-valuemax="100"
                ></div>
                </div>
            </div>
            </div>
        </div>
    </div> --}}
    <!--/ Sales Overview -->

    <!-- Revenue Generated -->
    <div class="col-lg-3 col-md-6 mb-4 col-12">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between mb-3">
                <h5 class="card-title mb-0">Total New Hired of current month</h5>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.companies.employees.new_hiring') }}">
                    <div class="d-flex align-items-center">
                        <div class="badge rounded-pill bg-label-primary me-3 p-2">
                            <i class="ti ti-user ti-sm"></i>
                        </div>
                        <div class="card-info">
                            <h5 class="mb-0" id="total-new-hired-count">Loading...</h5>
                            <small>Employees</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 col-12">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between mb-3">
                <h5 class="card-title mb-0">Total Terminations of current month</h5>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.companies.terminated_employees_of_current_month') }}">
                    <div class="d-flex align-items-center">
                        <div class="badge rounded-pill bg-label-primary me-3 p-2">
                            <i class="ti ti-user ti-sm"></i>
                        </div>
                        <div class="card-info">
                            <h5 class="mb-0" id="total-terminated-of-current-month-count">Loading...</h5>
                            <small>Employees</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!--/ Revenue Generated -->
</div>
