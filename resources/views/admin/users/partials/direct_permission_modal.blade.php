<div class="modal fade modal-lg" id="direct_permission_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-h">
                <div class="text-center">
                    <h1 class=" modal-header-title">{{ $title ?? "-" }}</h1>
                </div>
                <form id="add_role_form" class="form" action="#">
                    @csrf
                <div class="">
                    <div class="mh-375px scroll-y me-n7 pe-7">
                        <div class="modal-body scroll-y mx-lg-5 my-7">
                            <input type="hidden" name="user_id" value="{{ $id ?? null }}">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_role_header" data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px">
                                <div class="fv-row">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                            <!--begin::Table body-->
                                            <tbody class="text-gray-600 fw-semibold">
                                                <!--begin::Table row-->
                                                <tr>
                                              
                                                    <td class="text-gray-800">Administrator Access
                                                        <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Allows a full access to the system">
                                                            <i class="ki-outline ki-information fs-7"></i>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-9">
                                                            {{-- <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" /> --}}
                                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target=".form-check-input-permission" value="1">
                                                            <span class="form-check-label" for="kt_roles_select_all">Select all</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </td>
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                @if (isset($allPermissions) && !empty($allPermissions))
                                                @foreach ($allPermissions as $permission)
                                                <tr>
                                                    <!--begin::Label-->
                                                    <td class="text-gray-800">{{ isset($permission->label) && !empty($permission->label) ? ucfirst($permission->label) : '-' }} Management</td>
                                                    <!--end::Label-->
                                                    <!--begin::Options-->
                                                    <td>
                                                        <!--begin::Wrapper-->
                                                        <div class="d-flex">
                                                            <div class="row">
                                                                @foreach (SubPermissions($permission->label) as $sub_permission)
                                                                @php $label = explode('-', $sub_permission->name) @endphp
                                                                <!--begin::Checkbox-->
                                                                <div class="col-md-4 mb-5">
                                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                        <input class="form-check-input-permission form-check-input" type="checkbox" value="{{ $sub_permission->id ?? null }}" name="permissions[]" {{ isset($userPermissions) && !empty($userPermissions) && in_array($sub_permission->id, $userPermissions) ? "checked" : null }}/>
                                                                        <span class="form-check-label custom_pointer">{{ Str::ucfirst($label[1]) }}</span>
                                                                    </label>
                                                                </div>
                                                                <!--end::Checkbox-->
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </td>
                                                    <!--end::Options-->
                                                </tr>
                                                @endforeach
                                                @endif
                                                <!--end::Table row-->
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
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
                        <button type="button" onclick="storeDirectPermission($(this))" data-kt-users-permission-modal-action="submit" data-route="{{route('users.storeDirectPermission')}}" class="btn btn-primary permissionBtn">
                            <span class="indicator-label">Submit</span>
     
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
