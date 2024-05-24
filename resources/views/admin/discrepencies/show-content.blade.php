<table class="table table-bordered table-striped">
    <tr>
        <th>Company</th>
        <td>
            <span  class="fw-semibold text-info">{{ Str::ucfirst($company) ?? '' }}</span>
        </td>
    </tr>
    <tr>
        <th>Employee</th>
        <td>
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                        @if(!empty($model->hasEmployee->profile->profile))
                            <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model->hasEmployee->profile->profile }}" alt="Avatar" class="rounded-circle">
                        @else
                            <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <a href="{{ route('employees.show', $model->hasEmployee->slug) }}" class="text-body text-truncate">
                        <span class="fw-semibold">{{ Str::ucfirst($model->hasEmployee->first_name??'') }} {{ Str::ucfirst($model->hasEmployee->last_name??'') }}</span>
                    </a>
                    <small class="text-muted">{{ $model->hasEmployee->email??'-' }}</small>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>Type</th>
        <td>
            <span  class="badge bg-label-info">{{ Str::ucfirst($model->type) }}</span>
        </td>
    </tr>
    <tr>
        <th>Attendance Date</th>
        <td>
            @if(isset($model->hasAttendance) && !empty($model->hasAttendance->in_date))
                {{ date('d M Y', strtotime($model->hasAttendance->in_date)) }}
            @else
                -
            @endif
        </td>
    </tr>
    @if($model->type == 'lateIn')
        <tr>
            <th>Check In</th>
            <td>
                @if(isset($model->hasAttendance) && !empty($model->hasAttendance->in_date))
                    {{ date('h:i A', strtotime($model->hasAttendance->in_date)) }}
                @else
                    -
                @endif
            </td>
        </tr>
    @elseif($model->type == 'earlyout')
        <tr>
            <th>Check Out</th>
            <td>
                @if(isset($model->hasAttendance) && !empty($model->hasAttendance->in_date))
                    {{ date('h:i A', strtotime($model->hasAttendance->in_date)) }}
                @else
                    -
                @endif
            </td>
        </tr>
    @endif
    <tr>
        <th>Additional</th>
        <td>
            @if($model->is_additional)
                <span class="badge bg-label-warning" text-capitalized="">Additional</span>
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            @if($model->status==1)
                <span class="badge bg-label-success" text-capitalized="">Approved</span>
            @elseif($model->status==2)
                <span class="badge bg-label-danger">Rejected</span>
            @else
                <span class="badge bg-label-warning" text-capitalized="">Pending</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Applied At</th>
        <td>{{ date('d M Y h:i A', strtotime($model->created_at)) }}</td>
    </tr>
    <tr>
        <th>Reason</th>
        <td>{{ $model->description??'-' }}</td>
    </tr>
</table>
