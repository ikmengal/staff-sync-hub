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
                                Select a company to view attendance.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">

                @foreach ($companies as $index => $model)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <a href="{{ route('admin.companies.attendance', $model->company_key) }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row h-100">
                                   
                                            <div
                                                class="d-flex  h-100  mt-sm-0 mt-3">
                                               @include('admin.companies.logo',['model',$model])
                                               <h6 class="fw-normal mb-2">{{ $model->name }}
                                            </h6>
                                            </div>
                                      
                               

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
