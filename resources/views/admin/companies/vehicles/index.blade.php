@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')
@if(isset($company) && !empty($company))
    <input type="hidden" id="page_url" value="{{ route('admin.company.vehicles', $company) }}">
@else
    <input type="hidden" id="page_url" value="{{ route('admin.companies.vehicles') }}">
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
                <div class="col-md-2 mb-3">
                    <label for="">Department</label>
                    <select name="department" id="department" data-control="select2" class="select2 form-select department  unselectValue">
                   
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Company</label>
                    <select name="company" id="company" data-control="select2" class="select2 form-select company unselectValue">
                       
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
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Employee</th>
                                    <th>Company</th>
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
            loadPageData();
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
                serverSide: true,
                ajax: {
                    url: page_url + "?loaddata=yes",
                    type: "GET",
                    data: function (d) {
                        d.company = $('#company').val();
                        d.department = $("#department").val();
                   
                    },
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(error);
                        console.log(code);
                    }
                },
                columns: [
                {
                    data: 'vehicleName',
                    name: 'vehicleName'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'company',
                    name: 'company'
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
                                department.append('<option value="' +  val + '">' + val + '</option>');
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
        

        $(".searchBtn").click(function () {
          
          var table = $('.data_table').DataTable();
          table.ajax.reload(null, false)
      });
        //datatable
    </script>
@endpush
