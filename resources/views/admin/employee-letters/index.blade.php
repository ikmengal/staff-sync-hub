@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <input type="hidden" id="page_url" value="{{ route('employee-letters.index') }}">
    <input type="hidden" id="search_route" value="{{-- route('grievance.getSearchDataOnLoad') --}}">
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
                    <div class="col-md-3">
                        <label>Date Range</label>
                        <input type="text" class="form-control w-100 unselectValue" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" />
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="form-label" for="filter_status">Status</label>
                        <select name="filter_status" id="filter_status" class="select2 form-select filter_status unselectValue">
                            <option value="">All</option>
                            <option value="Active">Active</option>
                            <option value="De-Active">De Active</option>
                        </select>
                    </div>
                    <div class="col-md-3 mt-3 py-1">
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
                                        <th>Title</th>
                                        <th>effective_date</th>
                                        <th>validity_date</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="body" style="vertical-align:top"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Stocks List Table -->
            <!-- Show Detail Modal -->
            <div class="modal fade modal-add-new-cc" id="view-template-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl ">
                    <div class="modal-content p-0">
                        <div class="modal-header p-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="mb-4">
                                <h3 class="mb-2" id="modal-label"></h3>
                            </div>
                            <span id="show-content"></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Show Detail Modal -->
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
                var status = $("#status");
                var status = $("#status");
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
                    d.filter_status = $('#filter_status').val()
                    d.date_range = $('#flatpickr-range').val()
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
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'effective_date',
                    name: 'effective_date'
                }, 
                {
                    data: 'validity_date',
                    name: 'validity_date'
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

    $(document).on('click', '.show', function(){
        var route = $(this).attr('data-show-url');
        var company = $(this).attr('company-name');
        $.ajax({
            url: route,
            data : {company:company},
            type: 'GET',
            success: function(response) {
                $("#show-content").html(response);
                $("#show-detail-modal").modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    })

    $(document).on('change', '#title', function(){
            var title = $(this).val();
            if(title=='joining_letter'){
                $('.is-vehicle').html("");  
                $('.vehicle-content').html("");
                $('.validity-date').html("");
                
                var html = '<label class="form-label" for="is_vehicle">Vehicle <span class="text-danger">*</span></label>'+
                            '<select class="form-control is_vehicle" id="is_vehicle" name="is_vehicle">'+
                                '<option value="1">Yes</option>'+
                                '<option value="0" selected>No</option>'+
                            '</select>'+
                            '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                            '<span id="template_id_id_error" class="text-danger error"></span>';
                            
                            $('.is-vehicle').html(html);
                            
                var vhtml = '<label class="form-label" for="validity_date">Validity Date </label>'+
                            '<input type="date" name="validity_date" id="validity_date" class="form-control" />'+
                            '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                            '<span id="validity_date_error" class="text-danger error"></span>';
                            
                            $('.validity-date').html(vhtml);
            }else if(title=='vehical_letter'){
                $('.is-vehicle').html("");  
                $('.validity-date').html("");
                $('.vehicle-content').html("");
                
                var html = '<div class="col-12 col-md-12 mt-2">'+
                                '<label class="form-label" for="vehicle_name">Vehicle Name <span class="text-danger">*</span></label>'+
                                '<input type="text" class="form-control" id="vehicle_name" name="vehicle_name" placeholder="Enter vehicle name">'+
                                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                                '<span id="vehicle_name_error" class="text-danger error"></span>'+
                            '</div>'+
                            '<div class="col-12 col-md-12 mt-2">'+
                                '<label class="form-label" for="vehicle_cc">Vehicle Engine Capacity (CC) <span class="text-danger">*</span></label>'+
                                '<input type="text" class="form-control" id="vehicle_cc" name="vehicle_cc" placeholder="Enter vehicle engine cc">'+
                                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                                '<span id="vehicle_cc_error" class="text-danger error"></span>'+
                            '</div>';
                $('.vehicle-content').html(html);
            }else{
                $('.is-vehicle').html("");  
                $('.vehicle-content').html("");
                $('.validity-date').html("");
            }
        });
        
        $(document).on('change', '.is_vehicle', function(){
            var is_vehicle = $(this).val();
            if(is_vehicle==1){
                var html = '<div class="col-12 col-md-12 mt-2">'+
                                '<label class="form-label" for="vehicle_name">Vehicle Name <span class="text-danger">*</span></label>'+
                                '<input type="text" class="form-control" id="vehicle_name" name="vehicle_name" placeholder="Enter vehicle name">'+
                                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                                '<span id="vehicle_name_error" class="text-danger error"></span>'+
                            '</div>'+
                            '<div class="col-12 col-md-12 mt-2">'+
                                '<label class="form-label" for="vehicle_cc">Vehicle Engine Capacity (CC) <span class="text-danger">*</span></label>'+
                                '<input type="text" class="form-control" id="vehicle_cc" name="vehicle_cc" placeholder="Enter vehicle engine cc">'+
                                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                                '<span id="vehicle_cc_error" class="text-danger error"></span>'+
                            '</div>';
                $('.vehicle-content').html(html);
            }else{
                $('.vehicle-content').html("");
            }
        });
    
</script>
@endpush