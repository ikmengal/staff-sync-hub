@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')
@if(isset($data['status']) && $data['status']=='terminated_employees_of_current_month')
    <input type="hidden" id="page_url" value="{{ route('admin.companies.terminated_employees_of_current_month') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('admin.companies.terminated_employees') }}">
@endif
<input type="hidden" id="search_route" value="{{ route('admin.companies.getSearchDataOnLoad') }}">
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
            <div class="row p-3">
                <div class="col-md-3 mb-3">
                    <label for="">Company</label>
                    <select name="company" id="company" data-control="select2" class="select2 form-select company unselectValue">
                       
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="">Department</label>
                    <select name="department" id="department" data-control="select2" class="select2 form-select department  unselectValue">
                   
                    </select>
                </div>
              
                <div class="col-md-2 mb-3">
                    <label for="">Shift</label>
                    <select name="shift" id="shift" class="select2 form-select shift unselectValue">
                      
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Status</label>
                    <select name="status" id="status" class="select2 form-select status unselectValue">
               
                    </select>
                </div>
                <div class="col-md-2 mt-3 py-1">
                    <button type="button" class="btn btn-primary searchBtn me-2"><i
                        class="fa-solid fa-filter"></i></button>
                    <button type="button" class="btn btn-danger refreshBtn">Reset <i
                        class="fa-solid fa-filter"></i></button>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;min-height: 360px;">
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
                            <tbody id="body" style="vertical-align:top"></tbody>
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
            setTimeout(() => {
                getFilterDate()
            }, 1000);
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
                // serverSide: true,
                ajax: {
                    url: page_url + "?loaddata=yes",
                    type: "GET",
                    data: function (d) {
                        d.company = $('#company').val();
                        d.department = $("#department").val();
                        d.shift = $("#shift").val();
                        d.status = $("#status").val();
                    },
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

        function getFilterDate() {
            var route = $("#search_route").val();
            $.ajax({
                type: "get",
                url: route,
                success: function (res) {
                    var company = $("#company");
                    var department = $("#department");
                    var shift = $("#shift");
                    var status = $("#status");
                    company.empty();
                    if (res.success) {
               
                        if (res.data.companies.length !== 0) {
                            company.append('<option value="">Select Company</option>');
                            $.each(res.data.companies, function (ind, val) {
                                company.append('<option value="' +val.name+ '">' + val.name + '</option>');
                            });
                        }
                        if (res.data.departments.length !== 0) {
                            console.log(res.departments)
                            department.append('<option value="">Select Department</option>');
                            $.each(res.data.departments, function (ind, val) {
                                department.append('<option value="' +val+ '">' + val + '</option>');
                            });
                        }
                        if (res.data.shifts.length !== 0) {
                            shift.append('<option value="">Select Shift</option>');
                            $.each(res.data.shifts, function (ind, val) {
                                shift.append('<option value="' + val + '">' + val + '</option>');
                            });
                        }
                        if (res.data.statuses.length !== 0) {
                            status.append('<option value="">Select Status</option>');
                            $.each(res.data.statuses, function (ind, val) {
                                status.append('<option value="' + val.name + '">' + val.name + '</option>');
                            });
                        }
                    }
                }
            });
        }

        $(".refreshBtn").click(function (e) {
            e.preventDefault();
            $(".unselectValue").val(null).trigger('change');
            $(".emptyValue").val('');
            var table = $('.data_table').DataTable();
            table.ajax.reload(null, false)
        });

        $('input[type="search"]').on('keyup', function () {
            var table = $('.data_table').DataTable();
            table.search($(this).val()).draw();
        });


        if ($.fn.DataTable.isDataTable('.data_table')) {
                table.destroy();
            }
            $.fn.dataTable.ext.errMode = 'throw';

        $(".searchBtn").click(function () {
          
            var table = $('.data_table').DataTable();
            table.ajax.reload(null, false)
        });

        //datatable
    </script>
@endpush
