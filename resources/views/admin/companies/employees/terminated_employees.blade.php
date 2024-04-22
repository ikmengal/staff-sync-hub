@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')
@if(isset($data['status']) && $data['status']=='terminated_employees_of_current_month')
    <input type="hidden" id="page_url" value="{{ route('admin.companies.terminated_employees_of_current_month') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('admin.companies.terminated_employees') }}">
@endif
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0">
                            <span class="text-muted fw-light">Home /</span> {{ $data['title'] }}
                            @if(isset($company) && !empty($company))
                                <a href="{{ URL::previous() }}" class="btn btn-primary">
                                    <i class="fas fa-reply me-1"></i>
                                </a>
                            @endif
                        </h4>
                    </div>
                </div>
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
                                    <th>Employee</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Company</th>
                                    <th style="width:200px">Shift</th>
                                    <th>Employment</th>
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
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'Department',
                    name: 'Department'
                },
                {
                    data: 'Company',
                    name: 'Company'
                },
                {
                    data: 'shift',
                    name: 'shift'
                },
                {
                    data: 'emp_status',
                    name: 'emp_status'
                },
            ]
            });
        }
        //datatable
    </script>
@endpush
