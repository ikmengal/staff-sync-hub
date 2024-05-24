@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <input type="hidden" id="page_url" value="{{ route('mark_attendance.index') }}">
    <input type="hidden" id="search_route" value="{{ route('mark_attendance.getSearchDataOnLoad') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0">
                                <span class="text-muted fw-light">Home /</span> {{ $data['title'] }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Stocks List Table -->
            <div class="card mt-4">
                <div class="row p-3">
                    <div class="col-md-4">
                        <label for="form-label" for="company">Company</label>
                        <select name="company" id="company" data-control="select2" class="select2 form-select company unselectValue">
                            <option value="">All</option>
                            @if(isset($companies) && !empty($companies))
                                @foreach($companies as $index => $value)
                                    <option value="{{$value->id}}">{{$value->name ?? '-'}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="form-label" for="mark_type">Mark Type</label>
                        <select name="mark_type" id="mark_type" data-control="select2" class="select2 form-select mark_type unselectValue">
                            <option value="">All</option>
                            <option value="absent">Absent</option>
                            <option value="firsthalf">First half</option>
                            <option value="lateIn">Late In</option>
                            <option value="fullday">Full Day</option>
                        </select>
                    </div>
                    <div class="col-md-4 mt-3 py-1">
                        <button type="button" class="btn btn-primary searchBtn me-2"><i class="fa-solid fa-filter"></i></button>
                        <button type="button" class="btn btn-danger refreshBtn me-2">Reset&nbsp;<i class="fa-solid fa-filter"></i></button>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;min-height: 360px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company</th>
                                        <th>Employee</th>
                                        <th>Mark Type</th>
                                        <th>Adjustment Date</th>
                                        <th>Created At</th>
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

    function getFilterDate() {
        var route = $("#search_route").val();
        $.ajax({
            type: "get",
            url: route,
            success: function (res) {
                var company = $("#company");
                company.empty();
                if (res.success) {
                    if (res.data.companies.length !== 0) {
                        company.append('<option value="">All</option>');
                        $.each(res.data.companies, function (ind, val) {
                            company.append('<option value="' +val.name+ '">' + val.name + '</option>');
                        });
                    }
                }
            }
        });
    }

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
            searching: true,
            searchPanes: {
                filterChanged: function(count) {
                    $('.SPDetails').text(this.i18n('searchPanes.collapse', {
                        0: 'AdvancedFilter',
                        _: 'Advancedfilter (%d)'
                    }, count));
                }
            },
            ajax: {
                url: page_url + "?loaddata=yes",
                type: "GET",
                data: function(d) {
                    d.search = $('input[type="search"]').val()
                    d.company = $('#company').val()
                    d.mark_type = $('#mark_type').val()
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'company',
                    name: 'company'
                },
                {
                    data: 'employee_id',
                    name: 'employee_id'
                },
                {
                    data: 'mark_type',
                    name: 'mark_type'
                },
                {
                    data: 'attendance_date',
                    name: 'attendance_date'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ]
        });
    }
    //datatable

    $('input[type="search"]').on('keyup', function() {
        var table = $('.data_table').DataTable();
        table.search($(this).val()).draw();
    });

    $(".searchBtn").click(function() {
        var table = $('.data_table').DataTable();
        table.ajax.reload(null, false)
    });

    $(".refreshBtn").click(function(e) {
        e.preventDefault();
        $(".unselectValue").val(null).trigger('change');
        $(".emptyValue").val('');
        var table = $('.data_table').DataTable();
        table.ajax.reload(null, false)
    });

</script>
@endpush