@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')
<input type="hidden" id="page_url" value="{{ route('admin.companies') }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $data['title'] }}</h4>
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        <div class="dt-buttons btn-group flex-wrap">
                            <button class="btn btn-secondary add-new btn-primary mx-3" data-toggle="tooltip" data-placement="top" title="Add New Employee" id="add-btn" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#create-form-modal">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- Users List Table -->
        <div class="card mt-4">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th style="width:100px">Logo</th>
                                    <th>Company</th>
                                    <th>Total Employees</th>
                                    <th>Total Vehicles</th>
                                    <th>Head</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Action</th>
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
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            loadPageData()
        });
        //datatable
        function loadPageData() {
            var table = $('.data_table').DataTable();
            if ($.fn.DataTable.isDataTable('.data_table')) {
                table.destroy();
            }
            $.fn.dataTable.ext.errMode = 'throw';
            var page_url = $('#page_url').val();
            var table = $('.data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: page_url + "?loaddata=yes",
                    type: "GET",
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(error);
                        console.log(code);
                    }
                },
                columns: [
                    {
                        data: 'favicon',
                        name: 'favicon'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'total_employees',
                        name: 'total_employees'
                    },
                    {
                        data: 'total_vehicles',
                        name: 'total_vehicles'
                    },
                    {
                        data: 'head',
                        name: 'head'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        }
        //datatable
    </script>
@endpush
