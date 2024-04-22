<div class="row">
    <div class="col-lg-3 col-md-6 mb-4 col-12">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between mb-3">
                <h5 class="card-title mb-0">Total Offices</h5>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.companies') }}">
                    <div class="d-flex align-items-center">
                        <div class="badge rounded-pill bg-label-primary me-3 p-2">
                            <i class="ti ti-building ti-sm"></i>
                        </div>
                        <div class="card-info">
                            <h5 class="mb-0" id="companies-count">Loading...</h5>
                            <small>Offices</small>
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
                    <h5 class="card-title mb-0">Total Active Empoloyees</h5>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.companies.employees') }}">
                    <div class="d-flex align-items-center">
                        <div class="badge rounded-pill bg-label-primary me-3 p-2">
                            <i class="ti ti-user ti-sm"></i>
                        </div>
                        <div class="card-info">
                            <h5 class="mb-0" id="total-employees-count">Loading...</h5>
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
                    <h5 class="card-title mb-0">Total Terminated Employees</h5>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.companies.terminated_employees') }}">
                    <div class="d-flex align-items-center">
                        <div class="badge rounded-pill bg-label-primary me-3 p-2">
                            <i class="ti ti-chart-pie-2 ti-sm"></i>
                        </div>
                        <div class="card-info">
                            <h5 class="mb-0" id="total-terminated-employees-count">Loading...</h5>
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
                <h5 class="card-title mb-0">Total Vehicles</h5>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.companies.vehicles') }}">
                    <div class="d-flex align-items-center">
                        <div class="badge rounded-pill bg-label-primary me-3 p-2">
                            <i class="ti ti-car ti-sm"></i>
                        </div>
                        <div class="card-info">
                            <h5 class="mb-0" id="total-vehicles-count">Loading...</h5>
                            <small>Vehicles</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
