@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())
@section('content')
    <input type="hidden" id="page_url" value="{{ route('pre-employees.index') }}">
    <input type="hidden" id="search_route" value="{{ route('admin.companies.getSearchDataOnLoad') }}">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">

                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex row">

                    <div class="col-md-4 mt-md-0 mt-3">


                        <label>Select Company</label>
                        <select name="company" id="company" data-control="select2"
                            class="select2 form-select company unselectValue">

                        </select>

                    </div>

                    <div class="col-md-2 mt-md-0 mt-3 ">

                        <button class="btn btn-primary mt-4 " id="searchBtn"><i class="fa-solid fa-filter"></i></button>
                    </div>

                </div>
            </div>
            <div class="card">
                {{-- <div class="card-header d-flex justify-content-between">
                    <div></div>
                    <div class="mt-md-0 mt-3">
                        <button class="btn btn-success" id="exportBtn" data-route="{{route('pre.employees.export')}}">Export</button>
                    </div>
                </div> --}}



                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="datatables-users table border-top dataTable no-footer dtr-column data_table"
                                id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th>Applicant</th>
                                        <th>Applied Position</th>
                                        <th>Expected Salary</th>
                                        <th>Manager</th>
                                        <th>Applied At</th>
                                        <th>Status</th>
                                        <th>Is Exist</th>
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

            setTimeout(() => {
                getFilterDate();
            }, 1000);
            loadData();
        });
        //datatable
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }


        function getFilterDate() {
            var route = $("#search_route").val();

            $.ajax({
                type: "get",
                url: route,
                success: function(res) {
                    var company = $("#company");

                    company.empty();
                    if (res.success) {


                        if (res.data.companies.length !== 0) {
                            company.append('<option value="">Select Company</option>');
                            $.each(res.data.companies, function(ind, val) {
                                company.append('<option value="' + val.name + '">' + val.name +
                                    '</option>');
                            });
                        }



                    }
                }
            });
        }
        $(document).ready(function() {
            var page_url = $('#page_url').val();

            var table = $('.data_table').DataTable({
                processing: true,
                // serverSide: true,
                searching: true,
                smart: true,
                ajax: {
                    url: page_url + "?loaddata=yes",
                    type: "GET",
                    data: function(d) {
                        d.company = $('#company').val();

                    },
                },

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
                        data: 'applied_position',
                        name: 'applied_position'
                    },
                    {
                        data: 'expected_salary',
                        name: 'expected_salary'
                    },
                    {
                        data: 'manager_id',
                        name: 'manager_id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'is_exist',
                        name: 'is_exist'
                    }, {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
        });
        $(".refreshBtn").click(function(e) {
            e.preventDefault();
            $(".unselectValue").val(null).trigger('change');
            $(".emptyValue").val('');
            var table = $('.data_table').DataTable();
            table.ajax.reload(null, false)
        });
        $("#searchBtn").click(function() {

            var table = $('.data_table').DataTable();
            table.ajax.reload(null, false)
        });

        $('input[type="search"]').on('keyup', function() {
            var table = $('.data_table').DataTable();
            table.search($(this).val()).draw();
        });

        $("#exportBtn").on('click', function() {
      
            var route = $(this).data('route');

            var urlParams = new URLSearchParams(window.location.search);
            var month = urlParams.get('month');
            var year = urlParams.get('year');
            var company = urlParams.get('company');
            var slug = urlParams.get('slug');
            var currentDate = new Date();
            var formattedDate = currentDate.getFullYear() +
                ('0' + (currentDate.getMonth() + 1)).slice(-2) +
                ('0' + currentDate.getDate()).slice(-2);
            var formattedTime = ('0' + currentDate.getHours()).slice(-2) +
                ('0' + currentDate.getMinutes()).slice(-2) +
                ('0' + currentDate.getSeconds()).slice(-2);

            // Construct the dynamic file name
            var filename = 'pre_employees_report_' + formattedDate + '_' + formattedTime + '.csv';

            // Create a hidden anchor element
            var downloadLink = document.createElement('a');
            downloadLink.style.display = 'none';

            document.body.appendChild(downloadLink);

            // Set the href attribute to the download URL
            downloadLink.href = route + "?company=" + company + "&month=" + $month + "&year="+$year +"&slug=" + slug; 
            dd($downloadLink.href);
            // Set the download attribute to force download
            downloadLink.setAttribute('download', filename);

            // Trigger a click event on the anchor element
            downloadLink.click();
            // Clean up the anchor element
            document.body.removeChild(downloadLink);

        })
    </script>
@endpush
