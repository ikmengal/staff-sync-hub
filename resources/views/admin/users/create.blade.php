
<form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" id="createUserForm" data-method=""
    data-modal-id="editUserModal" data-url="{{ route('roles.store') }}">
    @csrf

    <span id="edit-content">

        <div class="col-12 mb-4">
            <label class="form-label" for="name">First Name <span class="text-danger">*</span></label>
            <input type="text" id="name" name="first_name" class="form-control"
                placeholder="Enter a role first_name" tabindex="-1" />
            <span id="first_name_error" class="text-danger"></span>
        </div>
        <div class="col-12 mb-4">
            <label class="form-label" for="name">Last Name <span class="text-danger">*</span></label>
            <input type="text" id="last_name" name="last_name" class="form-control"
                placeholder="Enter a role last_name" tabindex="-1" />
            <span id="last_name_error" class="text-danger"></span>
        </div>
        <div class="col-12 mb-4">
            <label class="form-label" for="name">Email <span class="text-danger">*</span></label>
            <input type="text" id="email" name="email" class="form-control" placeholder="Enter a email"
                tabindex="-1" />
            <span id="email_error" class="text-danger"></span>
        </div>



        <div class="col-12 mb-4">
            <label class="required fw-semibold fs-6 mb-5">Role</label>
            <span id="role_id_error" class="custom_error" style="color: #ea868f;"></span>
            @if (isset($roles) && !empty($roles))
                @foreach ($roles as $k => $item)
                    <div class="d-flex fv-row">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input role_id me-3" name="role_id[]" type="radio"
                                value="{{ $item->name ?? null }}"
                                id="kt_modal_update_role_option_{{ $item->id }}" />

                            <label class="form-check-label" for="kt_modal_update_role_option_{{ $item->id }}">
                                <div class="fw-bold text-gray-800">{{ $item->display_name ?? null }}
                                </div>
                                <div class="text-gray-600">{{ $item->short_description ?? null }}</div>
                            </label>
                        </div>
                    </div>
                    <div class='separator separator-dashed my-5'></div>
                @endforeach
            @endif

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