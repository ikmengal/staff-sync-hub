@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@section('content')
    <input type="hidden" id="page_url" value="{{ route('admin.salary-reports.details', $company_key) }}">
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
                <div class="row p-3">
                    <!-- <div class="col-md-4 mb-3">
                        <label for="">Companies</label>
                        <select name="company_key" id="company_key" class="select2 form-select company_key">
                            @if(!empty(getAllCompanies()))
                                @foreach(getAllCompanies() as $company)
                                    <option value="{{ $company->company_key }}" {{ $company->company_key==$company_key?'selected':'' }}>{{$company->name ?? ''}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div> -->
                    <div class="col-md-4 mb-3">
                        <label for="">Months</label>
                        <input type="month" class="form-control month_key">
                        <button type="button" class="btn btn-info btn-sm mt-2 me-2" id="resetMonth">Reset Month</button>
                    </div>
                    <div class="col-md-2 mt-3 py-1">
                        <a href="{{ URL::previous() }}" class="btn btn-primary me-2" title="Go Back">
                            <i class="fa-solid fa-reply"></i>
                        </a>
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
                                        <th scope="col" class="w-20">Month/Year</th>
                                        <th scope="col">total actual salary</th>
                                        <th scope="col">total car allowance</th>
                                        <th scope="col">total deduction</th>
                                        <th scope="col">total net salary</th>
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

        // $(document).on("change", ".company_key", function() {
        //     var id = $(this).val();
        //     // Update the URL parameter
        //     var newUrl = updateQueryStringParameter(window.location.href, 'company_key', id);
        //     history.pushState(null, '', newUrl);
        //     loadPageData()
        // });

        // Define the function to update the URL query string parameter
        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }

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
                    data: function(d) {
                        d.month_year = formatMonthYear($('.month_key').val());
                    },
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(error);
                        console.log(code);
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data: 'month_year', name:'month_year'},
                    {data: 'total_actual_salary', name:'total_actual_salary'},
                    {data: 'total_car_allowance', name:'total_car_allowance'},
                    {data: 'total_deduction', name:'total_deduction'},
                    {data: 'total_net_salary', name:'total_net_salary'},
                ]
            });
        }

        // Helper function to format the month input to MM/YYYY
        function formatMonthYear(value) {
            if (!value) return '';
            const [year, month] = value.split('-');
            return `${month}/${year}`;
        }

        // Event listeners for dropdown and month change
        $(document).on("change", ".month_key", function() {
            // var month = $('.month_key').val();
            var monthYear = formatMonthYear($('.month_key').val());
            // Update the URL parameter
            var newUrl = window.location.href;
            newUrl = updateQueryStringParameter(newUrl, 'month_year', monthYear);
            history.pushState(null, '', newUrl);
            loadPageData();
        });

        // Event listener for reset button
        $('#resetMonth').on('click', function() {
            $('.month_key').val('');
            var newUrl = updateQueryStringParameter(window.location.href, 'month_year', '');
            history.pushState(null, '', newUrl);
            loadPageData();
        });

        // Initial load
        loadPageData();
    </script>
@endpush
