<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($user->profile->profile))
                <img src="{{ asset('public/admin/assets/img/avatars').'/'.$user->profile->profile}}" alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <a href="" class="text-body text-truncate">
            <span class="fw-semibold">{{ $user->first_name??'' }} {{ $user->last_name??'' }} </span>
        </a>
       
    </div>
</div>