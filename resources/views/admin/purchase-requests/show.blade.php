@extends('admin.layouts.app')
@section('title', $title.' | '.appName())
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0">
                            <span class="text-muted fw-light">Home /</span> {{ $title }}
                            <a href="{{route('purchase-requests.index')}}" class="btn btn-primary waves-effect waves-light">
                              <i class="fas fa-reply me-1"></i>
                            </a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-mb-6 col-lg-7 col-12 mt-3">
          <div class="card h-100">
            <div class="card-body">
              <div class="row mb-4 mb-5">
                <div class="col-md-4">
                  <h5 class="card-title">Subject</h5>
                  <div>
                    <p class="my-2">{{ $record->subject ?? '-' }}</p>
                  </div>
                </div>
                <div class="col-md-12">
                  <h5 class="card-title">Description</h5>
                  <div>
                    <p class="my-2">{!! $record->description ?? '-' !!}</p>
                  </div>
                </div>
              </div>
              <div class="row gy-3">
                <div class="col-md-4 col-6 mb-4">
                  <div class="d-flex align-items-center">
                    <div class="card-info">
                      <h6 class="mb-2">Creator Email</h6>
                      <small>{{ $record->creator ?? '-' }}</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-6 mb-4">
                  <div class="d-flex align-items-center">
                    <div class="card-info">
                      <h6 class="mb-2">Company Name</h6>
                      <small>{{ $record->company->name ?? '-' }}</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-6 mb-4">
                  <div class="d-flex align-items-center">
                    <div class="card-info">
                      <h6 class="mb-2">Status</h6>
                      <small>
                        @if (isset($record->status) && $record->status == 1)
                          <span class="badge bg-label-warning" text-capitalized="">Pending</span>
                        @elseif(isset($record->status) && $record->status == 2)
                          <span class="badge bg-label-success" text-capitalized="">Approve</span>
                        @else
                          <span class="badge bg-label-danger" text-capitalized="">Rejected</span>
                        @endif
                      </small>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-6 mb-4">
                  <div class="d-flex align-items-center">
                    <div class="card-info">
                      <h6 class="mb-2">Modified By</h6>
                      <small>{{ $record->modifiedBy->first_name ?? '-' }} {{ $record->modifiedBy->last_name ?? '-' }}</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-6 mb-4">
                  <div class="d-flex align-items-center">
                    <div class="card-info">
                      <h6 class="mb-2">Modified At</h6>
                      <small>{{ isset($record->modified_at) && !empty($record->modified_at) ? date('d M Y, h:i A',strtotime($record->modified_at)) : '-' }}</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-6 mb-4">
                  <div class="d-flex align-items-center">
                    <div class="card-info">
                      <h6 class="mb-2">Remark</h6>
                      <small>{{ $record->remarks ?? '-' }}</small>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
@push('js')
  
@endpush
