<table class="table table-bordered table-striped">
        <tr>
            <th>Employee</th>
            <td>
                <div class="d-flex justify-content-start align-items-center user-name">
                    <div class="avatar-wrapper">
                        <div class="avatar avatar-sm me-3">
                            @if(!empty($userLeave->hasEmployee->profile->profile))
                                <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $userLeave->hasEmployee->profile->profile }}" alt="Avatar" class="rounded-circle img-avatar">
                            @else
                                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
                            @endif
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="{{ route('employees.show', $userLeave->hasEmployee->slug) }}" class="text-body text-truncate">
                            <span class="fw-semibold">{{ Str::ucfirst($userLeave->hasEmployee->first_name??'') }} {{ Str::ucfirst($userLeave->hasEmployee->last_name??'') }}</span>
                        </a>
                        <small class="text-muted">{{ $userLeave->hasEmployee->email??'-' }}</small>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
        <th>Phone Number</th>
        <td>
            @if(isset($userLeave->hasEmployee->profile) && !empty($userLeave->hasEmployee->profile->phone_number))
                {{ $userLeave->hasEmployee->profile->phone_number }}
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Working Shift</th>
        <td>
            @if(isset($userLeave->hasEmployee->userWorkingShift->workShift) && !empty($userLeave->hasEmployee->userWorkingShift->workShift->name))
                {{ $userLeave->hasEmployee->userWorkingShift->workShift->name }}
            @else
                @if(isset($userLeave->hasEmployee->departmentBridge->department->departmentWorkShift->workShift) && !empty($userLeave->hasEmployee->departmentBridge->department->departmentWorkShift->workShift->name))
                    {{ $userLeave->hasEmployee->departmentBridge->department->departmentWorkShift->workShift->name }}
                @else
                -
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <th>Date</th>
        <td>
            @if(date('d M, Y', strtotime($userLeave->start_at))==date('d M, Y', strtotime($userLeave->end_at)))
                <b>{{ date('d M, Y', strtotime($userLeave->start_at)) }}</b>
            @else
                <b>{{ date('d M, Y', strtotime($userLeave->start_at)) }}</b> to <b>{{ date('d M, Y', strtotime($userLeave->end_at)) }}</b>
            @endif
        </td>
    </tr>
    <tr>
        <th>Duration</th>
        <td>
            <span class="badge bg-label-info" text-capitalized="">{{ $userLeave->duration??0 }}</span>
        </td>
    </tr>
    <tr>
        <th>Leave Type</th>
        <td>
            @if(isset($userLeave->hasLeaveType) && !empty($userLeave->hasLeaveType->name))
                {{ $userLeave->hasLeaveType->name }}
            @else
            -
            @endif
        </td>
    </tr>
    
    <tr>
        <th>Created At</th>
        <td>{{ date('d F Y', strtotime($userLeave->created_at)) }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            @if($userLeave->status==1)
                <span class="badge bg-label-success" text-capitalized="">Approved</span>
            @elseif($userLeave->status==2)
                <span class="badge bg-label-danger" text-capitalized="">Rejected</span>
            @else
                <span class="badge bg-label-warning" text-capitalized="">Pending</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Reason</th>
        <td>{{ $userLeave->reason }}</td>
    </tr>
</table>
