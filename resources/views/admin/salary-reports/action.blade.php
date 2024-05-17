<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="{{ route('admin.salary-reports.details', ['company_key' => $model->company_key]) }}"
            class="dropdown-item"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-toggle="tooltip"
            data-placement="top"
            title="Company Salary Details"
            >
            View Details
        </a>
    </div>
</div>
