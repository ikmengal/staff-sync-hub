<div class="d-flex align-items-center">
    <div route="{{ route('grievances.show',$grievance->id) }}"
        class="btn btn-icon btn-label-info waves-effect show-detail"
        data-toggle="tooltip"
        data-placement="top"
        title="Show Detail"
        company-name="{{$grievance->company ?? ''}}"
        >
        <i class="ti ti-eye ti-xs"></i>
    </div>
</div>