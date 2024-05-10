<div class="d-flex align-items-center">
    @can('pre_employees-view')
        <a href="{{ route('pre-employees.show', [$employee->id,'company'=> $company]) }}" class=" btn btn-sm  btn-primary"> Details</a>
    @endcan
</div>