<div class="d-flex align-items-center">
    <a href="{{ route('admin.company.employees', $model->company_key) }}"
        class="btn btn-icon btn-label-info waves-effect"
        data-toggle="tooltip"
        data-placement="top"
        title="Company Employees {{ $model->company_key }}"
        >
        <i class="ti ti-users ti-xs"></i>
    </a>
    <a href="{{ route('admin.company.vehicles', $model->company_key) }}"
        class="btn btn-icon btn-label-warning waves-effect mx-2"
        data-toggle="tooltip"
        data-placement="top"
        title="Company Vehicles"
        >
        <i class="ti ti-car ti-xs"></i>
    </a>
    <a href="{{ route('admin.companies.attendance', $model->company_key) }}"
        class="btn btn-icon btn-label-warning waves-effect mx-2"
        data-toggle="tooltip"
        data-placement="top"
        title="Company Attendance"
        >
        <i class="ti ti-car ti-xs"></i>
    </a>
</div>
