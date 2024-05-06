@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mt-2"><span class="text-muted fw-light">User Profile /</span> Profile</h4>
                    </div>
                </div>
            </div>
        </div>
      
        <!-- Header -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        @if(isset($model->favicon) && !empty($model->favicon))
                            <img src="{{ $model->avatar_path }}{{ $model->favicon }}" style="width:100%" alt="Banner image" class="rounded-top img-fluid">
                        @else
                            <img src="{{$model->avatar_path}}" alt="Banner image" style="width:100%" class="rounded-top img-fluid">
                        @endif
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4 mt-n4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            @if(isset($model->avatar_path) && !empty($model->avatar_path))
                                <img src="{{ $model->avatar_path }}{{ $model->profile }}" style="width: 100px !important; height:100px !important" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img object-fit-cover"/>
                            @else
                                <img src="{{ $model->avatar_path }}/default.png" style="width: 100px !important; height:100px !important" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img"/>
                            @endif
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>{{ $model->name }}<span data-toggle="tooltip" data-placement="top" title="Employment ID">( {{ $model->employment_id??'-' }} )</span></h4>
                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2" >
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Designation">
                                            <i class="ti ti-color-swatch"></i>
                                            @if($model->employment_status=='Terminated')
                                                @if(isset($model->designation) && !empty($model->designation))
                                                    {{ $model->designation }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                @if(isset($model->designation) && !empty($model->designation))
                                                    {{ $model->designation }}
                                                @else
                                                    -
                                                @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Employment Status">
                                            <i class="ti ti-tag"></i>
                                            @if(isset($model->employment_status) && !empty($model->employment_status))
                                                <span class="badge bg-label-success"> {{ $model->employment_status }}</span>
                                            @else
                                            -
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Department">
                                            <i class="ti ti-building"></i>
                                            @if($model->employment_status=='Terminated')
                                                @if(isset($model->department) && !empty($model->department))
                                                    {{ $model->department }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                @if(isset($model->department) && !empty($model->department))
                                                    {{ $model->department }}
                                                @else
                                                    -
                                                @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Work Shift">
                                            <i class="ti ti-clock"></i>
                                            @if($model->employment_status=='Terminated')
                                                @if(isset($model->shift) && !empty($model->shift))
                                                    {{ $model->shift }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                @if(isset($model->shift) && !empty($model->shift))
                                                    {{ $model->shift }}
                                                @else
                                                    -
                                                @endif
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{ URL::previous() }}" class="btn btn-primary">
                                    <i class="fas fa-reply me-1"></i>Go Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->

        
    </div>
</div>
@endsection
@push('js')
@endpush