@extends('admin.layouts.app')
@section('title', $title.' | '.appName())
@section('content')


<div class="container-xxl flex-grow-1 container-p-y">
    <input type="hidden" id="page_url" value="{{ route('purchase-requests.index') }}">
    <input type="hidden" id="search_route" value="{{ route('purchase-requests.index') }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0">
                                <span class="text-muted fw-light">Home /</span> {{ $title }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stocks List Table -->
            <div class="card mt-4">
                <div class="row p-3">
                    <div class="col-md-4 mb-3">
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
                    <div class="col-md-4 mb-3">
                        <label for="form-label" for="filter_status">Status</label>
                        <select name="filter_status" id="filter_status" class="select2 form-select filter_status unselectValue">
                            <option value="">All</option>
                            <option value="1">Pending</option>
                            <option value="2">Approved</option>
                            <option value="3">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4 mt-3 py-1">
                        <button type="button" class="btn btn-primary searchBtn me-2"><i class="fa-solid fa-filter"></i></button>
                        <button type="button" class="btn btn-danger refreshBtn me-2">Reset&nbsp;<i class="fa-solid fa-filter"></i></button>
                        @can("purchase-requests-create")
                        <a href="javascript:;" class="btn btn-success add_purchase_request">
                            Add&nbsp;<i class="fa-solid fa-plus"></i>
                        </a>
                        @endcan
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px; min-height: 360px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company</th>
                                        <th>Creator</th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Status</th>
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
        </div>
        <!-- edit estimate modal  -->
        <div class="modal fade" id="add-purchase-request" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered modal-add-new-role">
                <div class="modal-content p-3 p-md-5">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div>
                        <h3 class="role-title mb-2">Add Purchase Request</h3>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="form-label" for="company">Company<span class="text-danger">*</span></label>
                                <select name="add_purchase_company" id="add_purchase_company" data-control="select2" class="select2 form-select company unselectValue">
                                    <option value="">Select Company</option>
                                    @if(isset($companies) && !empty($companies))
                                    @foreach($companies as $index => $value)
                                    <option value="{{$value->id}}">{{$value->name ?? '-'}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span id="add_purchase_company_error" class="text-danger error"></span>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="subject">Subject<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subject" placeholder="Enter Subject" name="subject" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="subject_error" class="text-danger error"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-1 fv-plugins-icon-container col-12">
                                <label class="form-label" for="description">Description<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" placeholder="Enter description" name="description" cols="15" rows="5"></textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="description_error" class="text-danger error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 action-btn">
                        <div class="demo-inline-spacing sub-btn">
                            <button type="submit" id="add_purchase_request" class="btn btn-primary me-sm-3 me-1  ">Submit</button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                        <div class="demo-inline-spacing loading-btn" style="display: none;">
                            <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                                <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- edit estimate modal  -->
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
                    data: 'creator',
                    name: 'creator'
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'status',
                    name: 'status'
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
        $('input[type="search"]').val('').trigger('keyup');
        var table = $('.data_table').DataTable();
        table.ajax.reload(null, false)
    });

    $(document).on("click", ".add_purchase_request", function() {
        $("#add-purchase-request").modal("show");
    });

    $(document).on("click", "#add_purchase_request", function() {
        $("#add_purchase_company_error").html("");
        var errors = [];

        var add_purchase_company = $("#add_purchase_company").val();

        if (!add_purchase_company) {
            errors.push(1)
            $("#add_purchase_company_error").html("Please Company ");
        } else {
            $("#add_purchase_company_error").html("");
        }

        var subject = $("#subject").val();

        if (!subject) {
            errors.push(1)
            $("#subject_error").html("Please enter subject");
        } else {
            $("#subject_error").html("");
        }

        var description = $("#description").val();

        if (!description) {
            errors.push(1)
            $("#description_error").html("Please enter description");
        } else {
            $("#description_error").html("");
        }

        if (errors.length > 0) {
            return false;
        } else {
            $.ajax({
                url: "{{route('purchase-requests.store')}}",
                method: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    company_id: add_purchase_company,
                    subject: subject,
                    description: description,
                },
                beforeSend: function() {
                    console.log("processing");
                },
                success: function(res) {
                    if (res.success == true) {
                        $("#add-purchase-request").modal("hide");

                        $("#subject").val('');
                        $("#description").val('');
                        $("#add_purchase_company").val('').trigger('change');
                        loadPageData();
                        Swal.fire({
                            text: res.message,
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            text: res.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        text: error,
                        icon: "error"
                    });
                }
            });
        }
    });
</script>
@endpush