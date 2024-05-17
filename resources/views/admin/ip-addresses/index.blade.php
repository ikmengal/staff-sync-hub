@extends('admin.layouts.app')

@section('title', $title . ' - ' . appName())

@section('content')
    <input type="hidden" id="page_url" value="{{ route('ip-addresses.index') }}">
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
                            <button id="add-btn" data-toggle="tooltip" data-placement="top" title="Add IP Address"
                                data-url="{{ route('ip-addresses.store') }}"
                                class="btn add-new btn-primary mb-3 mb-md-0 mx-3" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                data-bs-target="#addIpAddressModal">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> Add IP Address </span>
                                </span>
                            </button>
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
                                id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;min-height: 360px;">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th>Name</th>
                                        <th>Ip Address</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="body" style="vertical-align:top"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <!-- Add Permission Modal -->
                <div class="modal fade" id="addIpAddressModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-3 p-md-5">
                            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <div class="modal-body">
                                <div class="text-center mb-4">
                                    <h3 class="mb-2" id="modal-label">Add Ip Address</h3>
                                </div>
                                <form id="create-form" data-modal-id="addIPAddressModal" class="row">
                                    @csrf
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="name">IP Address<span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="IP Address Name" autofocus />
                                        <span id="name_error" class="text-danger error"></span>
                                    </div>
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
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Add Permission Modal -->
            </div>
        </div>
    </div>
    @include('admin.ip-addresses.edit_model')
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
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        });

        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#create-form").on('submit', function(e) {
            e.preventDefault();

            var name = $('#name').val().trim();
            if (!name) {
                $('#name_error').html('The name field is required.');
                return;
            } else {
                $('#name_error').html('');
            }

            var formData = $(this).serialize();

            $.ajax({
                route: "{{ route('ip-addresses.store') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {

                    if (response.success) {
                        var table = $('.data_table').DataTable();
                        table.ajax.reload(null, false)
                        $('#create-form')[0].reset();
                        $('#addIpAddressModal').modal('hide');
                        Swal.fire({
                            text: response.message,
                            icon: "success"
                        });
                    } else {
                        if (response.validation === false) {
                            $(".error").html("")
                            $.each(response.message, function(index, value) {
                                const fieldName = index;
                                const errorElement = $(`#${fieldName}_error`);
                                errorElement.html(value[0]);
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var response = xhr.responseJSON;
                    alert(response.message)
                }
            });
        });

        function deletePermission(e) {
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
                            _token: '{{ csrf_token() }}',
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

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).attr('data-id');
            var route = $(this).attr('data-edit-url');

            $.ajax({
                type: 'GET',
                url: route,
                success: function(res) {
                    $('.modal_body_content').empty();
                    $('.modal_body_content').html(res.view);
                    $('#editIPAddressModal').modal('show');
                },
                error: function(xhr, status, error) {
                    var response = xhr.responseText;
                    alert(response.message)
                }
            });
        })

        $(document).on('submit', "#editIPAddressForm", function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            var route = $(this).attr('data-url');

            $.ajax({
                type: 'PUT',
                url: route,
                data: formData,
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        var table = $('.data_table').DataTable();
                        table.ajax.reload(null, false)
                        $('#editIPAddressForm')[0].reset();
                        $('#editIPAddressModal').modal('hide');
                        Swal.fire({
                            text: res.message,
                            icon: "success"
                        });
                    } else {
                        if (res.validation === false) {
                            $(".error").html("")
                            $.each(res.message, function(index, value) {
                                const fieldName = index;
                                const errorElement = $(`#${fieldName}_error`);
                                errorElement.html(value[0]);
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var response = xhr.responseJSON;
                    alert(response.message)
                }
            });
        })
    </script>
@endpush
