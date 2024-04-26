<form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" id="editPermissionForm" data-method=""
    data-modal-id="editPermissionModal" data-url={{ route('permissions.update', $permission->id) }}
   >
    @csrf

    <span id="edit-content">
        
        <div class="col-12 mb-4">
            <label class="form-label" for="name">Permission Label <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control"
                tabindex="-1" value="{{ $permission->label }}" readonly />
            <span id="name_error" class="text-danger"></span>
        </div>
        <div class="col-12 mb-4">
            <label class="form-label" for="custom_permission">Custome Permission Name <span class="text-danger">*</span></label>
            <input type="text" id="custom_permission" name="custom_permission" class="form-control" placeholder="Enter a role name"
                tabindex="-1" value=""  />
            <span id="custom_permission_error" class="text-danger error"></span>
        </div>


       
    </span>

    <div class="col-12 mt-3 action-btn">
        <div class="demo-inline-spacing sub-btn">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                Cancel
            </button>
        </div>
        <div class="demo-inline-spacing loading-btn" style="display: none;">
            <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                Cancel
            </button>
        </div>
    </div>
</form>
