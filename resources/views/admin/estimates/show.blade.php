@extends('admin.layouts.app')
@section('title', $title.' | '.appName())
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0">
                                <span class="text-muted fw-light">Home /</span> {{ $title }}
                                <a href="{{route('estimates.index')}}" class="btn btn-primary waves-effect waves-light">
                                  <i class="fas fa-reply me-1"></i>
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
              <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Purchase Request</h6>
                        <p>{{ $record->requestData->subject ?? '' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Company</h6>
                        <p>{{ $record->company->name ?? '' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Title</h6>
                        <p>{{ $record->title ?? '' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Price</h6>
                        <p>{{ $record->price ?? '' }}</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h6>Description</h6>
                        <p>{{ $record->description ?? '' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Status</h6>
                        <p>
                          <span class="badge bg-label-{{ $record->getStatus->class ?? '' }}">{{ $record->getStatus->name ?? '' }}</span>
                        </p>
                    </div>
                    @if(isset($record) && !empty($record->attachments))
                      <div class="col-md-12 row mt-2">
                        <h6 for="">Attachments</h6>
                        @foreach ($record->attachments as $key => $item)
                          <div class="col-md-2 mb-3">
                            <img src="{{ asset('public/attachments/estimates')}}/{{$item->file}}" alt="No Image" class="img-fluid">
                          </div>
                        @endforeach
                      </div>
                    @endif
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection