@extends('admin.layouts.app')
@section('title', $title . '-' . appName())

@section('content')
    <input type="hidden" id="page_url" value="">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        <h4 class="fw-bold pb-4 border-bottom"><span class="text-muted fw-light">Home /</span>
                            {{ $title }}</h4>
                        <p class="mt-2 mb-0">
                            A role provided access to predefined menus and features so that depending on <br /> assigned
                            role an administrator can have access to what user needs.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            @foreach ($roles as $role)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-normal mb-2">Total <span class="">{{ count($role->users) }}</span> users
                                </h6>
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                    @php $counter = 0; @endphp
                                    @foreach ($role->users as $role_user)
                                        @if ($counter <= 5)
                                            @php $counter++; @endphp
                                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                                title="{{ $role_user->first_name }} {{ $role_user->last_name }}"
                                                class="avatar avatar-sm pull-up">
                                                @if (isset($role_user->profile) && !empty($role_user->profile->profile))
                                                    <img class="rounded-circle"
                                                        src="{{ resize(asset('public/admin/assets/img/avatars') . '/' . $role_user->profile->profile, null) }}"
                                                        alt="Avatar" />
                                                @else
                                                    <img class="rounded-circle"
                                                        src="{{ asset('public/admin/assets/img/avatars/default.png') }}"
                                                        alt="Avatar" />
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-1">
                                <div class="role-heading">
                                    <h4 class="mb-1">{{ $role->name }}</h4>
                                    <button type="button" class="btn btn-sm btn-primary my-1"
                                        data-modal-id="kt_modal_view_users" onclick="showAllUsersModal($(this))"
                                        data-id="{{ $role->id ?? null }}"
                                        data-route="{{ route('roles.showAllUsers') }}">View Users</button>
                                    <a href="javascript:;" title="Edit Record"
                                        data-edit-url="{{ route('roles.edit', $role->id) }}" data-id="{{ $role->id }}"
                                        class=" edit-btn" type="button">
                                        <span><i class="fa fa-edit"></i> Edit Role</span>
                                    </a>
                                    {{-- <br />
                                <a href="{{ route('roles.edit_role', $role->id) }}" data-toggle="tooltip" data-placement="top" title="Edit Record" class="role-edit-modal">
                                    <span><i class="fa fa-edit"></i> Edit Role & Permissions</span>
                                </a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="row h-100">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                                <img src="{{ asset('public/admin') }}/assets/img/illustrations/add-new-roles.png"
                                    class="img-fluid mt-sm-4 mt-md-0" alt="add-new-roles" width="83" />
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="card-body text-sm-end text-center ps-sm-0">
                                <button id="add-btn" data-toggle="tooltip" data-placement="top" title="Add Role"
                                    data-url="{{ route('roles.store') }}" class="btn add-new btn-primary mb-md-0 mx-3"
                                    tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                    data-bs-target="#addRoleModal">
                                    <span>Add Role</span>
                                </button>
                                <p class="mb-0 mt-1">Add role, if it does not exist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">


                    <!-- Add Role Modal -->
                    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-add-new-role">
                            <div class="modal-content p-3 p-md-5">
                                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="modal-body">
                                    <div class="text-center mb-4">
                                        <h3 class="role-title mb-2" id="modal-label">Add New Role</h3>
                                        <p class="text-muted">Set role permissions</p>
                                    </div>
                                    <!-- Add role form -->
                                    <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" id="addRoleForm"
                                        data-method="" data-modal-id="addRoleModal" id="create-form">
                                        @csrf

                                        <span id="edit-content">
                                            <div class="col-12 mb-4">
                                                <label class="form-label" for="name">Role Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="name" name="name" class="form-control"
                                                    placeholder="Enter a role name" tabindex="-1" />
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
                                                                    <i class="ti ti-info-circle" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Allows a full access to the system"></i>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="selectAll" />
                                                                        <label class="form-check-label" for="selectAll">
                                                                            Select All </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @foreach ($models as $permission)
                                                                <tr>
                                                                    <td class="text-nowrap fw-semibold">
                                                                        {{ ucfirst($permission->label) }} Management</td>
                                                                    <td>
                                                                        <div class="d-flex">
                                                                            @foreach (SubPermissions($permission->label) as $sub_permission)
                                                                                @php $label = explode('-', $sub_permission->name) @endphp
                                                                                <div class="form-check me-3 me-lg-5">
                                                                                    <input class="form-check-input"
                                                                                        name="permissions[]"
                                                                                        value="{{ $sub_permission->name }}"
                                                                                        type="checkbox"
                                                                                        id="userManagementRead-{{ $sub_permission->id }}" />
                                                                                    <label class="form-check-label"
                                                                                        for="userManagementRead-{{ $sub_permission->id }}">
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
                                                <button type="submit"
                                                    class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                                                <button type="reset" class="btn btn-label-secondary btn-reset"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    Cancel
                                                </button>
                                            </div>
                                            <div class="demo-inline-spacing loading-btn" style="display: none;">
                                                <button class="btn btn-primary waves-effect waves-light" type="button"
                                                    disabled="">
                                                    <span class="spinner-border me-1" role="status"
                                                        aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                                <button type="reset" class="btn btn-label-secondary btn-reset"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <!--/ Add role form -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Add Role Modal -->

                    <!-- Add New Employee Modal -->

                    <!-- Add New Employee Modal -->

                    <!-- Add Salary Modal -->
                    <div class="modal fade" id="promote-employee-modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-top modal-add-new-role">
                            <div class="modal-content p-3 p-md-5">
                                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="modal-body">
                                    <div class="text-center mb-4">
                                        <h3 class="role-title mb-2" id="salary-title-label"></h3>
                                    </div>
                                    <!-- Add role form -->
                                    <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method=""
                                        data-modal-id="promote-employee-modal" id="promote-employee-form">
                                        @csrf

                                        <span id="promote-content"></span>

                                        <div class="col-12 mt-3 action-btn">
                                            <div class="demo-inline-spacing sub-btn">
                                                <button type="submit"
                                                    class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                                                <button type="reset" class="btn btn-label-secondary btn-reset"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    Cancel
                                                </button>
                                            </div>
                                            <div class="demo-inline-spacing loading-btn" style="display: none;">
                                                <button class="btn btn-primary waves-effect waves-light" type="button"
                                                    disabled="">
                                                    <span class="spinner-border me-1" role="status"
                                                        aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                                <button type="reset" class="btn btn-label-secondary btn-reset"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <!--/ Add role form -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Add Salary Modal -->

                    <!-- Add Direct Permission Modal -->
                    <div class="modal fade" id="edit-direct-permission-modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-add-new-role">
                            <div class="modal-content p-3 p-md-5">
                                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="modal-body">
                                    <div class="text-center mb-4">
                                        <h3 class="role-title mb-2" id="modal-label"></h3>
                                        <p class="text-muted">Set user permissions</p>
                                    </div>
                                    <!-- Add role form -->
                                    <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method=""
                                        data-modal-id="edit-direct-permission-modal" id="create-form">
                                        @csrf

                                        <span id="edit-content"></span>

                                        <div class="col-12 mt-3 action-btn">
                                            <div class="demo-inline-spacing sub-btn">
                                                <button type="submit"
                                                    class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                                                <button type="reset" class="btn btn-label-secondary btn-reset"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    Cancel
                                                </button>
                                            </div>
                                            <div class="demo-inline-spacing loading-btn" style="display: none;">
                                                <button class="btn btn-primary waves-effect waves-light" type="button"
                                                    disabled="">
                                                    <span class="spinner-border me-1" role="status"
                                                        aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                                <button type="reset" class="btn btn-label-secondary btn-reset"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <!--/ Add role form -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Add Role Modal -->


                </div>
            </div>
        </div>
        <div class="showUserModal"></div>
        @include('admin.roles.edit')
    @endsection
    @push('js')
        <script>
            $("#selectAll").click(function() {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
        </script>


        <script>
            $(document).on('click', '.edit-btn', function() {

                var id = $(this).attr('data-id');
                var route = $(this).attr('data-edit-url');

                $.ajax({
                    type: 'GET',
                    url: route,
                    success: function(res) {

                        $('.modal_body_content').empty();
                        $('.modal_body_content').html(res.view);
                        $('#editRoleModal').modal('show');

                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });



            })
            //datatable
            var table = $('.data_table').DataTable();
            $(document).on('submit', "#addRoleForm", function(e) {
                e.preventDefault(); // Prevent the default form submission behavior

                // Serialize form data
                var formData = $(this).serialize();

                // AJAX request
                $.ajax({
                    route: "{{ route('roles.store') }}", // Replace '/submit-url' with your actual endpoint URL
                    type: 'POST',
                    data: formData,
                    dataType: 'json', // The type of data you expect back from the server
                    success: function(res) {
                        // Handle the successful response from the server
                        if (res.success) {
                            setTimeout(() => {
                            location.reload(true);
                        }, 1000)
                            $('#addRoleForm')[0].reset();
                            $('#addRoleModal').modal('hide');
                        } else {
                            if (res.validation === false) {
                                $(".error").html("")
                                // Loop through each error message
                                $.each(res.message, function(index, value) {
                                    // Display error below respective input field
                                    const fieldName = index;
                                    const errorElement = $(`#${fieldName}_error`);
                                    errorElement.html(value[0]);
                                });
                            }
                        }




                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });

            })


            $(document).on('change', '#selectAll', function() {

                $('.childCheckBox').prop('checked', $(this).prop('checked'));
            });
            $('.childCheckbox').change(function() {
                if (!$(this).prop('checked')) {
                    $('#selectAll').prop('checked', false);
                } else {
                    var allChecked = true;
                    $('.childCheckbox').each(function() {
                        if (!$(this).prop('checked')) {
                            allChecked = false;
                            return false; // Break out of the loop
                        }
                    });
                    $('#selectAll').prop('checked', allChecked);
                }
            });


            $(document).on('submit', "#editRoleForm", function(e) {

                e.preventDefault(); // Prevent the default form submission behavior

                // Serialize form data
                var formData = $(this).serialize();
                var route = $(this).attr('data-url');
                // AJAX request
                $.ajax({
                    type: 'PUT',
                    url: route, // Replace '/submit-url' with your actual endpoint URL

                    data: formData,
                    dataType: 'json', // The type of data you expect back from the server
                    success: function(res) {
                        // Handle the successful response from the server
                        setTimeout(() => {
                            location.reload(true);
                        }, 1000)
                        Swal.fire({
                            text: res.message,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },

                        })
                        $('#editRoleModal').modal('hide');

                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });

            })
            if ($.fn.DataTable.isDataTable('.data_table')) {
                table.destroy();
            }
            $.fn.dataTable.ext.errMode = 'throw';

            function showAllUsersModal(event) {
                var route = event.data('route');
                var id = event.data('id');
                var modalId = event.data('modal-id');
                $.ajax({
                    type: "get",
                    url: route,
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res.success == true) {
                            $('.showUserModal').empty();
                            $('.showUserModal').html(res.view);
                            $("#" + modalId).modal('show');
                        }
                    }
                });
            }
        </script>
    @endpush
