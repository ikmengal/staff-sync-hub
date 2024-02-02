@extends('admin.layouts.app')
@section('title', $title.'-' . appName())

@section('content')
    <input type="hidden" id="page_url" value="{{ route(Route::currentRouteName(), $role->id) }}">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users List Table -->
            <div class="card mt-4">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row p-3">
                            <div class="col-md-4 mb-3">
                                <label for="">Model Name</label>
                                <select name="model_name" id="model_name" class="select2 form-select model_name">
                                    <option value="all">All</option>
                                    @if (!empty($models) && count($models) > 0)
                                        @foreach ($models as $model)
                                            <option value="{{ $model->label }}">{{ ucfirst($model->label) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="container">
                            <table class="table">
                                <tr>
                                    <td colspan="2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll" />
                                            <label class="form-check-label" for="selectAll"> Select All </label>
                                        </div>
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </table>
                            <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th>Module</th>
                                        <th>
                                            Permissions

                                        </th>
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
        $("#selectAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(document).ready(function() {
            loadPageData()
        });
        $(document).on("change", ".model_name", function() {
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
                // paging: false,
                // searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: page_url + "?loaddata=yes",
                    type: "GET",
                    data: function(d) {
                        d.model_name = $('.model_name').val();
                        d.search = $('input[type="search"]').val();
                    },
                    error: function(xhr, error, code) {
                        console.log(xhr);
                        console.log(error);
                        console.log(code);
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'label',
                        name: 'label'
                    },
                    {
                        data: 'sub_permissions',
                        name: 'sub_permissions'
                    }
                ]
            });
        }
        //datatable
    </script>
@endpush
