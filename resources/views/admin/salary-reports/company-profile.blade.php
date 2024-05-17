<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($company['favicon']))
                <img src="{{ $company['favicon'] }}" alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <span class="fw-semibold">{{ $company['name'] }}</span>
        <small class="emp_post text-truncate text-muted">
            {{ $company['email']??'-' }}
        </small>
    </div>
</div>
