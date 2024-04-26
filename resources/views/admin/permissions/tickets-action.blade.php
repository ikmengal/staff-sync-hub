<div class="d-flex align-items-center">
    <button class="btn btn-icon edit-btn btn-active-light-primary w-30px h-30px me-3"   data-type="edit" data-edit-url="{{ route('permissions.edit',$permission->label) }}">
        <i class="ti ti-edit ti-xs mx-2"></i>
    </button>
    <a data-toggle="tooltip" data-placement="top" onclick="deletePermission($(this))" title="Delete Record" href="javascript:;"  class="btn btn-icon btn-label-primary waves-effect deleteBtn" data-slug="{{ $permission->label }}" data-del-url="{{ route('permissions.destroy', $permission->label) }}" data-label="{{ $permission->label }}">
        <i class="ti ti-trash ti-xs mx-2"></i>
    </a>
</div>
