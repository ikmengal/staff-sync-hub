@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@section('content')
    <input type="hidden" id="page_url" value="{{ route('admin.salary-reports') }}">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No#</th>
                                        <th scope="col" class="w-20">Company</th>
                                        <th scope="col">total actual salary</th>
                                        <th scope="col">total car allowance</th>
                                        <th scope="col">total deduction</th>
                                        <th scope="col">total net salary</th>
                                        <th scope="col">Action</th>
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
    <script type="text/javascript">
        $(document).ready(function() {
            loadPageData()
        });

        function loadPageData() {
            var table = $('.data_table').DataTable();
            if ($.fn.DataTable.isDataTable('.data_table')) {
                table.destroy();
            }
            var page_url = $('#page_url').val();
            var table = $('.data_table').DataTable({
                processing:true,
                serverSide:true,
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
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data: 'company', name:'company'},
                    {data: 'total_actual_salary', name:'total_actual_salary'},
                    {data: 'total_car_allowance', name:'total_car_allowance'},
                    {data: 'total_deduction', name:'total_deduction'},
                    {data: 'total_net_salary', name:'total_net_salary'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }
    </script>
@endpush
