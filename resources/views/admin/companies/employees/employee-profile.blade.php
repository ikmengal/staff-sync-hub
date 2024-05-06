<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($employee->profile))
            
                <img src="{{ getEmpImage($employee->base_url , 'public/admin/assets/img/avatars/' , $employee->profile)  }} " alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <span class="fw-semibold">{{ $employee->name??'' }}({{ $employee->employment_id??'-' }})</span>
        <small class="emp_post text-truncate text-muted">
            {{ $employee->designation }}
        </small>
    </div>
</div>
