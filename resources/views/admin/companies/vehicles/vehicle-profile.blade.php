<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($model->vehicleThumbnail))
                <img src="{{ $model->base_url }}/public/admin/assets/img/avatars/{{ $model->vehicleThumbnail }}" alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/car-default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <span class="fw-semibold">{{ $model->vehicleName??'' }} ({{ $model->vehicleModelYear??'-' }})</span>
        <small class="emp_post text-truncate text-muted">
            {{ $model->vehicleCc }} CC
        </small>
    </div>
</div>
