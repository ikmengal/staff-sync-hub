<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($model->head->profile))
                <img src="{{ getEmpImage($model->base_url, '/public/admin/assets/img/avatars/', $model->head->profile) }}" alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <span class="fw-semibold">{{ $model->head->name??'-' }}</span>
        <small class="emp_post text-truncate text-muted">
            @if(isset($model->head->designation) && !empty($model->head->designation))
                {{ $model->head->designation }}
            @else
            -
            @endif
        </small>
    </div>
</div>
