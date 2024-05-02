
<form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" id="editUserForm" data-method=""
    data-modal-id="editUserModal" data-url="{{ route('users.update',$edit->id) }}">
    @csrf

    <span id="edit-content">
<div class="row">
    <div class="col-6 mb-4">
        <label class="form-label" for="name">First Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="first_name" class="form-control"
            placeholder="Enter a role first_name" tabindex="-1" value="{{$edit->first_name}}" />
        <span id="first_name_error" class="text-danger first_name_error"></span>
    </div>
    <div class="col-6 mb-4">
        <label class="form-label" for="name">Last Name <span class="text-danger">*</span></label>
        <input type="text" id="last_name" name="last_name" class="form-control"
            placeholder="Enter a role last_name" value="{{$edit->last_name}}" tabindex="-1" />
        <span id="last_name_error" class="text-danger last_name_error"></span>
    </div>

</div>
        <div class="row">
            <div class="col-6 mb-4">
                <label class="form-label" for="name">Email <span class="text-danger">*</span></label>
                <input type="text" id="email" name="email" value="{{$edit->email}}" class="form-control" placeholder="Enter a email"
                    tabindex="-1" />
                <span id="email_error" class="text-danger email_error"></span>
            </div>
            <div class="col-6 mb-4">
                <label class="form-label" for="name">User Type <span class="text-danger">*</span></label>
                <select class="form-control select2" name="user_type">
                    <option value="">Select</option>
                    <option value="1" @if($edit->user_for_portal != null && $edit->user_for_api == null) selected @endif>Portal User</option>
                    <option value="2" @if($edit->user_for_portal == null && $edit->user_for_api != null) selected @endif>API User</option>
                    <option value="3" @if($edit->user_for_portal != null && $edit->user_for_api != null) selected @endif>Both</option>
                </select>
                <span id="user_type_error" class="text-danger user_type_error"></span>
            </div>

        </div>
        <div class="row">
            <div class="col-6 mb-4">
                 <label class="form-label" for="password">Password</label>
                 <input name="password" type="password" class="form-control" placeholder="Enter Password" >
                 <span class="text-danger password_error" id="password_error" ></span>
            </div>
            <div class="col-6 mb-4">
                <label class="form-label" for="password">Confirm Password</label>
                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password">
            </div>
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
                                id="kt_modal_update_role_option_{{ $item->id }}" {{ isset($edit->roles) && !empty($edit->roles) && in_array($item->id, $edit->roles->pluck('id')->toArray()) ? "checked" : '' }} />

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
