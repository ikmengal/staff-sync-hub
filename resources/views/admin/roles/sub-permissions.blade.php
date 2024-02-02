<div class="d-flex">
    @foreach (SubPermissions($permission->label) as $sub_permission)
        @php $label = explode('-', $sub_permission->name) @endphp
        <div class="form-check me-3 me-lg-5">
            @php $bool = false @endphp
            @foreach ($role_permissions as $role_permission)
                @if($role_permission==$sub_permission->name)
                    @php $bool = true; @endphp
                @endif
            @endforeach
            @if($bool)
                <input class="form-check-input" checked name="permissions[]" value="{{ $sub_permission->id }}" type="checkbox" id="userManagementRead-{{ $sub_permission->id }}" />
            @else
                <input class="form-check-input" name="permissions[]" value="{{ $sub_permission->id }}" type="checkbox" id="userManagementRead-{{ $sub_permission->id }}" />
            @endif
            <label class="form-check-label" for="userManagementRead-{{ $sub_permission->id }}"> {{ Str::ucfirst($label[1]) }}</label>
        </div>
    @endforeach
</div>
