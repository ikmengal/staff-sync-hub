{{-- <div class="card mt-12">
    <div class="row">
        <div class="card-datatable table-responsive">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="container">
                    <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Anonymous</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $gravience->hasCreator->first_name. ' ' .$gravience->hasCreator->last_name ?? '' }}</td>
                                <td>{{ $gravience->hasUser->first_name. ' ' .$gravience->hasUser->last_name ?? '' }}</td>
                                <td>{{ $gravience->description ?? '' }}</td>
                                <td>{{ $gravience->anonymous == 1 ? 'Yes' : 'Nn' }}</td>
                                <td>{{ $gravience->status== 1 ? 'Active' : 'De-Active' }}</td>
                                <td>{{ date('F d,Y', strtotime($gravience->created_at)) ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="row mb-3 fv-plugins-icon-container">
    <div class="col-6">
        <h3 class="form-label">Company Name</h3>
    </div>
    <div class="col-6">
        <h6 class="">{{ $company ?? '' }}</h6>
    </div>
</div>
<div class="row fv-plugins-icon-container">
    <div class="col-6">
        <h3 class="form-label">User</h3>
    </div>
    <div class="col-6">
        <h6 class="">{{ $gravience->hasUser->first_name. ' ' .$gravience->hasUser->last_name ?? '' }}</h6>
    </div>
</div>
<div class="row fv-plugins-icon-container">
    <div class="col-6">
        <h3 class="form-label">Creator Name</h3>
    </div>
    <div class="col-6">
        <h6 class="">{{ $gravience->hasCreator->first_name. ' ' .$gravience->hasCreator->last_name ?? '' }}</h6>
    </div>
</div>
<div class="row fv-plugins-icon-container">
    <div class="col-6">
        <h3 class="form-label">Craeted At</h3>
    </div>
    <div class="col-6">
        <h6 class="">{{ date('F d,Y', strtotime($gravience->created_at)) ?? '' }}</h6>
    </div>
</div>
<div class="row fv-plugins-icon-container">
    <div class="col-6">
        <h3 class="form-label">Anonymous</h3>
    </div>
    <div class="col-6">
        <h6 class="">{{ $gravience->anonymous== 1 ? 'Yes' : 'No' }}</h6>
    </div>
</div>
<div class="row fv-plugins-icon-container">
    <div class="col-6">
        <h3 class="form-label">Status</h3>
    </div>
    <div class="col-6">
        <h6 class="">{{ $gravience->status== 1 ? 'Active' : 'De-Active' }}</h6>
    </div>
</div>
<div class="row mt-3 fv-plugins-icon-container">
    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" name="description" id="description" placeholder="Enter description" readonly>{{ $gravience->description ?? '' }}</textarea>
    </div>
</div>
<script>
    CKEDITOR.replace('description');
</script>
