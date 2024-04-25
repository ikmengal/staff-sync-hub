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
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mb-6 col-lg-7 col-12 mt-3">
            <div class="card h-100">
              <div class="card-body">
                <div class="row gy-3">
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                      <div class="card-info">
                        <h6 class="mb-2">Stock Title</h6>
                        <small>{{ $stock->title ?? '' }}</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                      <div class="card-info">
                        <h6 class="mb-2">Stock Creator</h6>
                        <small>{{ $stock->hasUSer->first_name ?? '' }} {{ $stock->hasUSer->last_name ?? '' }}</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                      <div class="card-info">
                        <h6 class="mb-2">Company Name</h6>
                        <small>{{ $stock->hasCompany->name ?? '' }}</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                      <div class="card-info">
                        <h6 class="mb-2">Stock Quantity</h6>
                        <small>{{ $stock->quantity ?? '' }}</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex align-items-end row">
                <div class="col-6">
                  <div class="card-body text-nowrap">
                    <h5 class="card-title mb-2">Description</h5>
                    <p class="mb-2">{{ $stock->description ?? '' }}</p>
                  </div>
                </div>
                <div class="col-6">
                    <div class="card-body text-nowrap">
                      <h5 class="card-title mb-2">Status , {{$stock->status ?? ''}} </h5>
                      <p class="mb-2">{{ $stock->remarks ?? '' }}</p>
                    </div>
                  </div>
              </div>
              <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                  <h5 class="mb-0">Stock Images</h5>
                </div>
              </div>
              <div class="card-body">
                <div class="border rounded p-3 mt-5">
                    @if(isset($stock->hasImages) && !blank($stock->hasImages))
                    <div class="row gap-4 gap-sm-0">
                            @foreach ($stock->hasImages as $image)
                                <div class="col-12 col-sm-2">
                                    <img class="card-img" src="{{asset('public/admin/assets/img/stock')}}/{{$image->image??'' }}" id="image" alt="No Image" height="200px" />
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center">
                                <h3> No Image </h3>
                            </div>
                        </div>
                    </div>
                @endif
              </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
  
@endpush
