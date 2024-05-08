<form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" id="editRoleForm" data-method="" data-modal-id="editRoleModal" data-url={{ route('roles.update', $role->id) }} data-id="{{ $role->id }}">
    @csrf

    <span id="edit-content">
        <div class="col-12 mb-4">
            <label class="form-label" for="name">Role Name <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter a role name" tabindex="-1" value="{{ $role->name }}" readonly />
            <span id="name_error" class="text-danger"></span>
        </div>
        <div class="col-12">
            <h5>Role Permissions</h5>
            <!-- Permission table -->
            <div class="table-responsive">
                <table class="table table-flush-spacing">
                    <tbody>
                        <tr>
                            <td class="text-nowrap fw-semibold">
                                Administrator Access
                                <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll" />
                                    <label class="form-check-label" for="selectAll">
                                        Select All </label>
                                </div>
                            </td>
                        </tr>
                        @foreach ($models as $permission)
                        <tr>
                            <td class="text-nowrap fw-semibold">
                                {{ ucfirst($permission->label) }} Management
                            </td>
                            <td>
                                <div class="d-flex">
                                    @foreach (SubPermissions($permission->label) as $sub_permission)
                                    @php $label = explode('-', $sub_permission->name) @endphp
                                    <div class="form-check me-3 me-lg-5">
                                        <input class="form-check-input childCheckBox" type="checkbox" value="{{ $sub_permission->name ?? null }}" @if (isset($role_permissions) && !empty($role_permissions)) @foreach ($role_permissions as $val) @if ($val==$sub_permission->name){{ 'checked' }} @endif
                                        @endforeach
                                        @endif
                                        name="permissions[]" />
                                        <label class="form-check-label" for="userManagementRead-{{ $sub_permission->id }}">
                                            {{ Str::ucfirst($label[1]) }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Permission table -->
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