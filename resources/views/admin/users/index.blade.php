@extends('admin.layouts.app')
@section('title', $title . '-' . appName())

@section('content')
    <input type="hidden" id="page_url" value="{{ route('users.index') }}">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dt-buttons btn-group flex-wrap float-end mt-4">
                            @can('users-create')
                            <button id="add-btn" data-modal-id="createUserModal" data-toggle="tooltip"
                                data-placement="top" title="Add User" data-url="{{ route('users.create') }}"
                                class="btn add-new btn-primary mb-3 mb-md-0 mx-3" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> Add User </span>
                                </span>
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table
                                class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive"
                                id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th>Name</th>
                                        <th>Roles</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div id="permissionModal"></div>
    @include('admin.users.partials.create_modal')
    @include('admin.users.partials.edit_modal')
@endsection
@push('js')
    <script>
        //datatable
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        $(document).ready(function() {
            var page_url = $('#page_url').val();
            var table = $('.data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: page_url + "?loaddata=yes",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        //create user modal 
        $(document).on('click', "#add-btn", function() {
            var route = $(this).attr('data-url');


            var modalId = $(this).attr('data-modal-id');
            $.ajax({
                type: "get",
                url: route,

                success: function(res) {
                    if (res.success == true) {
                        $('.modal-body-content').empty();
                        $('.modal-body-content').html(res.view);
                        $("#" + modalId).modal('show');
                    }
                }
            });
        })
        //store user 
        $(document).on('submit', "#createUserForm", function(e) {
            e.preventDefault(); // Prevent the default form submission behavior

            // Serialize form data
            var formData = $(this).serialize();

            // AJAX request
            $.ajax({
                route: "{{ route('users.store') }}", // Replace '/submit-url' with your actual endpoint URL
                type: 'POST',
                data: formData,
                dataType: 'json', // The type of data you expect back from the server
                success: function(res) {
                    // Handle the successful response from the server
                    if (res.success) {
                        var table = $('.data_table').DataTable();
                        table.ajax.reload(null, false)
                        $('#createUserForm')[0].reset();
                        $('#createUserModal').modal('hide');
                        Swal.fire({
                            text: "User Created Successfully",
                            icon: "success"
                        });
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
                    var response = xhr.responseJSON;
                    alert(response.message);
                }
            });

        })

        //user edit modal
        $(document).on('click', ".edit-btn", function() {


            var route = $(this).attr('data-edit-url');


            $.ajax({
                type: 'GET',
                url: route,
                success: function(res) {

                    $('.modal-body-content').empty();
                    $('.modal-body-content').html(res.view);
                    $('#editUserModal').modal('show');

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });

        })

        //update user
        $(document).on('submit', "#editUserForm", function(e) {

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
                    if (res.success) {
                        var table = $('.data_table').DataTable();
                        table.ajax.reload(null, false)
                        $('#editUserForm')[0].reset();
                        $('#editUserModal').modal('hide');
                        Swal.fire({
                            text: "User Updated Successfully",
                            icon: "success"
                        });
                    } else {
                        if (res.validation === false) {
                            $(".error").html("")
                            // Loop through each error message
                            $.each(res.message, function(index, value) {
                                // Display error below respective input field
                                const fieldName = index;
                                const errorElement = $(`.${fieldName}_error`);
                              
                               
                                errorElement.html(value[0]);

                            });
                        }
                    }

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    var response = xhr.responseJSON;
                    alert(response.message);
                }
            });

        })
        //
        function deleteUser(e) {
            var route = e.attr('data-del-url');
            var id = e.attr('data-label')
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: route,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}', // Add CSRF token
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your resource has been deleted.",
                                icon: "success"
                            });

                            var table = $('.data_table').DataTable();
                            table.ajax.reload(null, false)
                            // Optionally, you can reload the page or remove the deleted item from the UI
                        },
                        error: function(xhr, status, error) {
                            var response = xhr.responseJSON;
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        $(document).on("click", ".addPermission", function() {
            var route = $(this).attr('data-route');
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'GET',
                url: route,
                success: function(res) {
                    console.log(res)
                    $("#permissionModal").empty();
                    $("#permissionModal").html(res.view);
                    $("#direct_permission_modal").modal('show');

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });

        })

        function storeDirectPermission(event) {
            var formData = $("#add_role_form").serializeArray();
            var route = event.data('route');
            $.ajax({
                type: "POST",
                url: route,
                data: formData,
                beforeSend: function() {
                    $(".indicator-label").css({
                        'display': 'none'
                    });
                    $(".indicator-progress").css({
                        'display': 'block'
                    });
                    $(".permissionBtn").addClass('disabled');
                },
                success: function(res) {
                  
                    $(".permissionBtn").removeClass('disabled');
                    if (res.success == true) {
                        var table = $('.data_table').DataTable();
                        table.ajax.reload(null, false);
                   
                        $("#direct_permission_modal").modal('hide');
                    } else {
                      alert(res.message)
                        
                    }
                },
                error: function(xhr, status, error) {
                  
                    $(".permissionBtn").removeClass('disabled');
                    alert(res.message)
                        

                }
            });
        }
    </script>
@endpush
