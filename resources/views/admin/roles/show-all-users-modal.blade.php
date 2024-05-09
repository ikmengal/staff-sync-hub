<div class="modal fade" id="kt_modal_view_users" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                <div class="text-center mb-13">
                    <h1 class="mb-3 modal-header-title">{{ $title ?? "-" }}</h1>
                </div>
                <div class="mb-15">
                    <div class="mh-375px scroll-y me-n7 pe-7">
                        @if (isset($users) && !empty($users))
                            @foreach ($users as $record)
                            <div class="d-flex flex-stack py-2 border-bottom border-gray-300 border-bottom-dashed">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px symbol-circle">
                                        @if (isset($record->profile) && !empty($record->profile->profile))
                                        <img src="{{ asset('public/admin/assets/img/avatars').'/'.$record->profile->profile }}" style="width:40px !important; height:40px !important;  object-fit:cover;" alt class="h-auto rounded-circle" />
                                        @else
                                        {!! getWordInitial($record->first_name) !!}
                                        @endif
                                    </div>
                                    <div class="ms-6">
                                        <a href="#" class="d-flex align-items-center fs-5 fw-bold text-gray-900 text-hover-primary">{{ $record->full_name ?? "-" }} 
                                        <span class="badge badge-light fs-8 fw-semibold ms-2">{{isset($record->roles) && !empty($record->roles) ? implode($record->roles->pluck('name')->toArray()) : "-"}}</span></a>
                                        <div class="fw-semibold text-muted" style="margin-left:30px;">{{ $record->email ?? "-" }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <center>
                            <span>No users have this role yet</span>
                        </center>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
