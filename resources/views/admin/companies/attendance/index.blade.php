@extends('admin.layouts.app')
@section('title', $data['title'] . ' | ' . appName())
@section('content')
    @if (isset($company) && !empty($company))
        <input type="hidden" id="page_url" value="{{ route('admin.companies.attendance', $company) }}">
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
                                @if ((isset($company) && !empty($company)) || (isset($data['status']) && !empty($data['status'])))
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
                    {{-- <div class="col-md-3 mb-3">
                    <label for="">Company</label>
                    <select name="company" id="company" data-control="select2" class="select2 form-select company unselectValue">
                       
                    </select>
                </div> --}}
                    <div class="col-md-2 mb-3">
                        <label for="">Select Month</label>
                        <input type="text" class="form-control flatpickr-input active" placeholder="" id="Slipbutton"
                            readonly="readonly">

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
                            <table
                                class="datatables-users table-border table border-top dataTable no-footer dtr-column data_table table-responsive"
                                id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info"
                                style="width: 1227px;min-height: 360px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Shift Time</th>
                                        <th>Punched In </th>
                                        <th>Punched Out</th>
                                        <th>Status</th>
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

            $('#Slipbutton').datepicker({
                format: 'mm/yyyy',
                startView: 'year',
                minViewMode: 'months',

            })
            loadPageData()

            setTimeout(() => {
                getFilterDate()
            }, 1000);

        });

        function getFilterDate() {
            var route = $("#search_route").val();
            $.ajax({
                type: "get",
                url: route,
                success: function(res) {
                    var company = $("#company");
                    var department = $("#department");
                    var shift = $("#shift");
                    var status = $("#status");
                    company.empty();
                    if (res.success) {
                        if (res.data.companies.length !== 0) {
                            company.append('<option value="">Select Company</option>');
                            $.each(res.data.companies, function(ind, val) {
                                company.append('<option value="' + val.company_key + '">' + val.name +
                                    '</option>');
                            });
                        }

                    }
                }
            });
        }

        $(".refreshBtn").click(function(e) {
            e.preventDefault();
            $(".unselectValue").val(null).trigger('change');
            $(".emptyValue").val('');
            var table = $('.data_table').DataTable();
            table.ajax.reload(null, false)
        });


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
                searching: true,
                ajax: {
                    url: page_url + "?loaddata=yes",
                    type: "GET",
                    data: function(d) {
                        d.company = $('#company').val();
                        d.date = $("#Slipbutton").val();

                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date',

                    },
                    {
                        data: 'shift_time',
                        name: 'shift_time',

                    },
                    {
                        data:'punch_in',
                        name:'punch_in',
                    },
                    {
                        data: 'punch_out',
                        name: 'punch_out'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },

                ]
            });
        }
        //datatable



        $(document).on('keyup', 'input[type="search"]', function() {
            var table = $('.data_table').DataTable();
            table.search($(this).val()).draw();
        })


        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        $.fn.dataTable.ext.errMode = 'throw';




        $(".searchBtn").click(function() {

            var table = $('.data_table').DataTable();
            table.ajax.reload(null, false)
        });
    </script>
@endpush
