<div class="d-flex align-items-center">
    @can('ip-addresses-edit')
        <button class="btn btn-icon edit-btn btn-label-info w-30px h-30px me-3" 
                data-type="edit"
                data-edit-url="{{ route('ip-addresses.edit',$model->id) }}"
            >
            <i class="ti ti-edit ti-xs mx-2"></i>
        </button>
    @endcan
    
    @can('ip-addresses-delete')
        <a 
            data-toggle="tooltip"
            data-placement="top"
            onclick="deletePermission($(this))"
            title="Delete Record"
            href="javascript:;" 
            class="btn btn-icon btn-label-primary waves-effect deleteBtn"
            data-slug="{{ $model->id }}"
            data-del-url="{{ route('ip-addresses.destroy', $model->id) }}"
            data-label="{{ $model->id }}"
        >
            <i class="ti ti-trash ti-xs mx-2"></i>
        </a>
    @endcan
</div>
