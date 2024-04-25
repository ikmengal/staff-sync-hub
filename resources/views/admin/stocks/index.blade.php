@extends('admin.layouts.app')
@section('title', $title.' | '.appName())
@section('content')
<input type="hidden" id="page_url" value="{{ route('stocks.index') }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
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

        <!-- Users List Table -->
        <div class="card mt-4">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Craetor</th>
                                    <th>Company</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add New Employee Modal -->
        <div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered modal-add-new-role">
                <div class="modal-content p-3 p-md-5">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body">
                        <div class="mb-4">
                            <h3 class="role-title mb-2">Remark</h3>
                        </div>
                        <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="create-form-modal" id="create-form">
                            @csrf
                            <div class="row">
                                <div class="mb-1 fv-plugins-icon-container col-12">
                                    <label class="form-label" for="remark">Remark<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="remark" placeholder="Enter Remark" name="remark" cols="15" rows="5"></textarea>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="remark_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <input type="hidden" name="route" id="route" value="{{route('stock.status')}}">
                            <input type="hidden" name="status_data" id="status_data" value="1">
                            <input type="hidden" name="stock_status_id" id="stock_status_id" value="">
                            <div class="col-12 action-btn">
                                <div class="demo-inline-spacing sub-btn">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <!-- Add New Employee Modal -->
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
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'creator',
                    name: 'creator'
                },
                {
                    data: 'company',
                    name: 'company'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
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

        $(document).on('change', '#status', function() {
            var statusValue = $(this).val();
            var stockId = $(this).attr('stockId');
            if(statusValue == 2 ){
                $('#remark').html("Approved");
                $('#status_data').val(statusValue);
                $('#stock_status_id').val(stockId);
                $('#create-form-modal').modal('show');
            }else{
                $('#remark').html('');
                $('#status_data').val(statusValue);
                $('#stock_status_id').val(stockId);
                $('#create-form-modal').modal('show');
            }
        });

        $(document).on('click', '.submitBtn', function(event){
            event.preventDefault();
            var route = $('#route').val();
            var formData = $('#create-form').serialize();

            $.ajax({
                url: route,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success == true){
                        toastr.success(response.message);
                        $('#create-form-modal').modal('hide');
                        dataTable.ajax.reload('.data_table');
                    }else{
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endpush
