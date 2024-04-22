<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($model->favicon))
                <img src="{{ $model->base_url }}/public/admin/assets/img/favicon/{{ $model->favicon }}" alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
</div>
