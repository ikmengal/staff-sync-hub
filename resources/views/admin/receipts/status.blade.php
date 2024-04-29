@if(isset($model) && $model->status == 1)
    <div class="col-lg-12">
        <select id="status" name="status" class="select2 form-select" aria-describedby="statusHelp" stockId="{{ $model->id }}">
            <option value="1" {{ isset($model) && $model->status == 1 ? 'selected' : '' }} >Pending</option>
            <option value="2" {{ isset($model) && $model->status == 2 ? 'selected' : '' }} {{ $model->status != 1 ? 'disabled' : '' }}>Approved</option>
            <option value="3" {{ isset($model) && $model->status == 3 ? 'selected' : '' }} {{ $model->status != 1 ? 'disabled' : '' }}>Rejected</option>
        </select>
    </div>
@else
    <div class="col-lg-12">
        <select id="status" name="status" class="select2 form-select" aria-describedby="statusHelp" disabled>
            <option value="3" {{ isset($model) && $model->status == 3 ? 'selected' : '' }} >Rejected</option>
            <option value="2" {{ isset($model) && $model->status == 2 ? 'selected' : '' }} >Approved</option>
        </select>
    </div>
@endif