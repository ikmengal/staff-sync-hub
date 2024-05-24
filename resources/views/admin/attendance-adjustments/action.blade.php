<div class="d-flex align-items-center">
    <a href="javascript:;"
       class="btn btn-icon btn-view btn-label-secondary waves-effect me-2 show"
       data-toggle="tooltip"
       data-placement="top"
       title=""
       data-show-url="{{ route('mark_attendance.show', $model->id) }}"
       type="button"
       tabindex="0" aria-controls="DataTables_Table_0"
       type="button" data-bs-toggle="modal"
       data-bs-target="#view-template-modal"
       company-name="{{$model->company ?? ''}}"
       fdprocessedid="i1qq7b">
       <i class="ti ti-eye ti-xs"></i>
    </a>
</div>
