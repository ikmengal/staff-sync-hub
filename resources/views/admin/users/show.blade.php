@extends('admin.layouts.app')
@section('title', $title . '-' . appName())

@section('content')
    <input type="hidden" id="page_url" value="{{ route('users.index') }}">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                 
                </div>

              
            </div>


        </div>
    </div>

@endsection
