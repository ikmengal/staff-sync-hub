@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
<div class="card mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card-header">
                <h4 class="fw-bold pb-4 border-bottom"><span class="text-muted fw-light">Home /</span>
                    {{ $title }}</h4>
                <p class="mt-2 mb-0">
                  Select A Companies Show Attendance Summary.
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row g-4">
    
    @foreach ($companies as $index => $company)
        <div class="col-xl-4 col-lg-6 col-md-6">
            <a href="{{route('admin.companies.attendance',$company->company_key)}}">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-normal mb-2">{{$company->name}}
                        </h6>
                    
                    </div>
                  
                </div>
            </div>
            </a>
        </div>
    @endforeach
</div>
    </div>
</div>
@endsection