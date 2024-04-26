@if(isset($model) && !empty($model))
    <div class="col-lg-12">
        <select id="status" name="status" class="select2 form-select" aria-describedby="statusHelp" stockId="{{ $model->id }}">
            <option value="1">Pending</option>
            <option value="2">Approved</option>
            <option value="3">Rejected</option>
        </select>
    </div>
@endif