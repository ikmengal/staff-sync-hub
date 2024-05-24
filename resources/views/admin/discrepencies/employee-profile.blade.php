<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(isset($model->hasEmployee->profile) && !empty($model->hasEmployee->profile->profile))
                <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model->hasEmployee->profile->profile }}" alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <a href="{{-- route('employees.show', $model->id) --}}" class="text-body text-truncate">
            <span class="fw-semibold">{{ $model->first_name??'' }} {{ $model->last_name??'' }}</span>
        </a>
        <small class="text-muted">{{ $model->email??'-' }}</small>
    </div>
</div>
