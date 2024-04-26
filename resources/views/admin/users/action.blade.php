<div class="d-flex align-items-center">
    @can('users-edit')
    <button class="btn btn-icon edit-btn btn-active-light-primary w-30px h-30px me-3"   data-type="edit" data-edit-url="{{ route('users.edit',$user->id) }}">
        <i class="ti ti-edit ti-xs mx-2"></i>
    </button>
    @endcan
    @can('users-delete')
    <button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" onclick="deleteUser($(this))"   data-type="del" data-del-url="{{ route('users.destroy',$user->id) }}" data-id={{$user->id}}>
        <i class="ti ti-trash ti-xs mx-2"></i>
    </button>
        
    @endcan
   
</div>
