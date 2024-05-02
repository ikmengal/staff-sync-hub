<div class="modal fade modal-lg" id="updatePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-h">
                <div class="text-center">
                    <h1 class=" modal-header-title">{{ $title ?? '-' }}</h1>
                </div>
                <form id="updatePasswordForm" class="form" action="#">
                    @csrf
                    <div class="">
                        <div class="mh-375px scroll-y me-n7 pe-7">
                            <div class="modal-body scroll-y mx-lg-5 my-7">
                                <input type="hidden" name="user_id" value="{{ $edit->id ?? null }}">
                                <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll"
                                    data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                    data-kt-scroll-max-height="auto"
                                    data-kt-scroll-dependencies="#kt_modal_add_role_header"
                                    data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px">
                                    <div class="col-12 mb-4">
                                        <label class="form-label" for="password">Current Password<span
                                                class="text-danger">*</span></label>
                                        <input type="password" id="current_password" name="current_password"
                                            class="form-control" placeholder="Enter Current Password" tabindex="-1" />
                                        <span id="current_password_error"
                                            class="text-danger current_password_error"></span>
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label" for="password">New Password<span
                                                class="text-danger">*</span></label>
                                        <input type="password" id="new_password" name="password"
                                            class="form-control" placeholder="Enter New Password" tabindex="-1" />
                                        <span id="new_password_error" class="text-danger new_password_error"></span>
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label" for="password">Confirm Password</label>
                                        <input type="password" id="confirmation_password" name="password_confirmation"
                                            class="form-control" placeholder="Confirm Password">
                                        <span id="confirm_password" class="text-danger"></span>
                                    </div>

                                    <!--end::Permissions-->
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                    <button type="button" onclick="updatePassword($(this))"
                      data-route="{{ route('users.updatePassword') }}"
                        class="btn btn-primary updatePasswordBtn">
                        <span class="indicator-label">Submit</span>
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
