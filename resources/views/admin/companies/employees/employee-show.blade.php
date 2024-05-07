@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0">
                            <span class="text-muted fw-light">Home /</span> {{ $data['title'] }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        @if(isset($model['user']->profile->coverImage) && !empty($model['user']->profile->coverImage))
                            <img src="{{ asset('public/admin/assets/img/pages') }}/{{ $model['user']->profile->coverImage->image }}" style="width:100%" alt="Banner image" class="rounded-top img-fluid">
                        @else
                            <img src="{{ asset('public/admin/assets/img/pages/profile-banner.png') }}" alt="Banner image" style="width:100%" class="rounded-top img-fluid">
                        @endif
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4 mt-n4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            @if(isset($model['user']->profile) && !empty($model['user']->profile->profile))
                                <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model['user']->profile->profile }}" 
                                    onerror="this.onerror=null; this.src='{{ asset('public/admin') }}/default.png';" 
                                    style="width: 100px !important; height:100px !important" 
                                    alt="user image" 
                                    class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img object-fit-cover"/>

                            @else
                                <img src="{{ asset('public/admin') }}/default.png" style="width: 100px !important; height:100px !important" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img"/>
                            @endif
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>{{ $model['user']->first_name }} {{ $model['user']->last_name }} <span data-toggle="tooltip" data-placement="top" title="Employment ID">( {{ $model['user']->profile->employment_id??'-' }} )</span></h4>
                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2" >
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Designation">
                                            <i class="ti ti-color-swatch"></i>
                                            @if($model['user']->employeeStatus->employmentStatus->name=='Terminated')
                                                @if(isset($model['user']->jobHistoryTerminate->designation->title) && !empty($model['user']->jobHistoryTerminate->designation->title))
                                                    {{ $model['user']->jobHistoryTerminate->designation->title }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                @if(isset($model['user']->jobHistory->designation->title) && !empty($model['user']->jobHistory->designation->title))
                                                    {{ $model['user']->jobHistory->designation->title }}
                                                @else
                                                    -
                                                @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Employment Status">
                                            <i class="ti ti-tag"></i>
                                            @if(isset($model['user']->employeeStatus->employmentStatus) && !empty($model['user']->employeeStatus->employmentStatus->name))
                                                <span class="badge bg-label-{{ $model['user']->employeeStatus->employmentStatus->class }}"> {{ $model['user']->employeeStatus->employmentStatus->name }}</span>
                                            @else
                                            -
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Department">
                                            <i class="ti ti-building"></i>
                                            @if($model['user']->employeeStatus->employmentStatus->name=='Terminated')
                                                @if(isset($model['user']->departmentBridgeTerminate->department) && !empty($model['user']->departmentBridgeTerminate->department->name))
                                                    {{ $model['user']->departmentBridgeTerminate->department->name }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                @if(isset($model['user']->departmentBridge->department) && !empty($model['user']->departmentBridge->department->name))
                                                    {{ $model['user']->departmentBridge->department->name }}
                                                @else
                                                    -
                                                @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Work Shift">
                                            <i class="ti ti-clock"></i>
                                            @if($model['user']->employeeStatus->employmentStatus->name=='Terminated')
                                                @if(isset($model['user']->userWorkingShiftTerminate->workShift) && !empty($model['user']->userWorkingShiftTerminate->workShift->name))
                                                    {{ $model['user']->userWorkingShiftTerminate->workShift->name }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                @if(isset($model['user']->userWorkingShift->workShift) && !empty($model['user']->userWorkingShift->workShift->name))
                                                    {{ $model['user']->userWorkingShift->workShift->name }}
                                                @else
                                                    -
                                                @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Joining Date">
                                            <i class="ti ti-calendar"></i>
                                            @if($model['user']->employeeStatus->employmentStatus->name=='Terminated')
                                                @if(isset($model['user']->jobHistoryTerminate) && !empty($model['user']->jobHistoryTerminate->joining_date))
                                                    Joined {{ date('d M Y', strtotime($model['user']->jobHistoryTerminate->joining_date)) }}
                                                @else
                                                -
                                                @endif
                                            @else
                                                @if(isset($model['user']->jobHistory) && !empty($model['user']->jobHistory->joining_date))
                                                    Joined {{ date('d M Y', strtotime($model['user']->jobHistory->joining_date)) }}
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

        <!-- Navbar pills -->
        <div class="row">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
                        <button
                        type="button"
                        class="nav-link active"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-top-profile"
                        aria-controls="navs-top-profile"
                        aria-selected="true"
                        >
                        <i class="ti-xs ti ti-user-check me-1"></i> Profile
                        </button>
                    </li>
                    <li class="nav-item">
                        <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-top-bank-details"
                        aria-controls="navs-top-bank-details"
                        aria-selected="true"
                        >
                        <i class="fa fa-building-columns me-1"></i> Bank Account
                        </button>
                    </li>
                    <li class="nav-item">
                        <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-top-job-history"
                        aria-controls="navs-top-job-history"
                        aria-selected="true"
                        >
                        <i class="ti ti-cell"></i>Job History
                        </button>
                    </li>
                    <li class="nav-item">
                        <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-top-cnic"
                        aria-controls="navs-top-cnic"
                        aria-selected="true"
                        >
                        <i class="fa fa-id-card"></i>&nbsp CNIC
                        </button>
                    </li>
                </ul>
                <div class="tab-content bg-transparent shadow-none p-0">
                    <div class="tab-pane fade show active" id="navs-top-profile" role="tabpanel">
                        <div class="row">
                            <!--Employee Detail-->
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="card-text text-uppercase">Personal Details </small>
                                            @if(Auth::user()->hasRole('Admin'))
                                                <a href="javascript:;"
                                                    class="edit-btn btn btn-label-primary btn-sm"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Edit Employee"
                                                    data-edit-url="{{ route('employees.edit', $model['user']->slug) }}"
                                                    data-url="{{ route('employees.update', $model['user']->slug) }}"
                                                    tabindex="0" aria-controls="DataTables_Table_0"
                                                    type="button" data-bs-toggle="modal"
                                                    data-bs-target="#create-form-modal"
                                                    >
                                                    <i class="ti ti-edit ti-xs"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="info-container">
                                            <ul class="list-unstyled">
                                                <li class="py-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-tag"></i>
                                                        Employee ID:
                                                    </span>
                                                    <span>
                                                        @if(isset($model['user']->profile) && !empty($model['user']->profile->employment_id))
                                                        {{ $model['user']->profile->employment_id }}
                                                        @else
                                                        -
                                                        @endif
                                                    </span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-user"></i>
                                                        Full Name:</span>
                                                    <span>{{ $model['user']->first_name??'' }} {{ $model['user']->last_name??'' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-message"></i>
                                                        Email:</span>
                                                    <span>{{ $model['user']->email??'-' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-phone"></i>
                                                        Phone Number:</span>
                                                    <span>{{ $model['user']->profile->phone_number??'-' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-user"></i>
                                                        Gender:</span>
                                                    <span>{{ Str::ucfirst($model['user']->profile->gender)??'-' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-calendar"></i>
                                                        Birthday:</span>
                                                    <span>{{ $model['user']->profile->date_of_birth??'-' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-user"></i>
                                                        About:
                                                        </span>
                                                    <span class="text-justify">{{ $model['user']->profile->about_me??'-' }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Emergency Contacts and Address-->
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <!--Emergency-->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <small class="card-text text-uppercase">Emergency Contacts</small>
                                        <div class="info-container pt-3">
                                            <div class="content py-primary">
                                                @if(isset($model['user_emergency_contacts']) && !empty($model['user_emergency_contacts']))
                                                    @foreach ($model['user_emergency_contacts'] as $user_emergency_contact)
                                                        @php $contact_details =
                                                        json_decode($user_emergency_contact->value); @endphp
                                                        <div class="col-12">
                                                            <div class="cardMaster border p-3 rounded mb-3">
                                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                                    <div class="card-information">
                                                                        <dl class="row mb-0">
                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Name:</dt>
                                                                            <dd class="col-sm-8">{{ isset($contact_details->name)?$contact_details->name:'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Relation:</dt>
                                                                            <dd class="col-sm-8">{{ isset($contact_details->relationship)?$contact_details->relationship:'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Phone Number:</dt>
                                                                            <dd class="col-sm-8">{{ isset($contact_details->phone_number)?$contact_details->phone_number:'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Address:</dt>
                                                                            <dd class="col-sm-8">{{ isset($contact_details->address_details)?$contact_details->address_details:'' }}</dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    Not Added Yet
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Address-->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <small class="card-text text-uppercase">Address Information</small>
                                        <div class="info-container pt-3">
                                            <div class="content py-primary">
                                                @if(!isset($model['user_current_address']) && empty($model['user_current_address']) && !isset($model['user_permanent_address']) && empty($model['user_permanent_address']))
                                                    Not Added Yet
                                                @else
                                                    @if(isset($model['user_permanent_address']) && !empty($model['user_permanent_address']))
                                                        @php $permanent_address = json_decode($model['user_permanent_address']['value']); @endphp
                                                        @php $permanent_address = json_decode($model['user_permanent_address']['value']); @endphp
                                                        <div class="col-12">
                                                            <div class="cardMaster border p-3 rounded mb-3">
                                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                                    <div class="card-information">
                                                                        <h4 class="mb-2">Permanent Address</h4>
                                                                        <dl class="row mt-4 mb-0">
                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Address:</dt>
                                                                            <dd class="col-sm-8">{{ $permanent_address->details??'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">City:</dt>
                                                                            <dd class="col-sm-8">{{ $permanent_address->city??'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Area:</dt>
                                                                            <dd class="col-sm-8">{{ $permanent_address->area??'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">State:</dt>
                                                                            <dd class="col-sm-8">{{ $permanent_address->state??'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Zip Code:</dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $permanent_address->zip_code??'' }}
                                                                            </dd>
                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Country:</dt>
                                                                            <dd class="col-sm-8">
                                                                                <span class="text-capitalize">{{ $permanent_address->country??'' }}</span>
                                                                            </dd>
                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Phone Number:</dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $permanent_address->phone_number??'' }}
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if(isset($model['user_current_address']) && !empty($model['user_current_address']))
                                                        @php $current_address = json_decode($model['user_current_address']['value']); @endphp
                                                        <div class="col-12">
                                                            <div class="cardMaster border p-3 rounded mb-3">
                                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                                    <div class="card-information">
                                                                        <h4 class="mb-2">Current Address</h4>
                                                                        <dl class="row mt-4 mb-0">
                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Address:</dt>
                                                                            <dd class="col-sm-8">{{ $current_address->details??'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">City:</dt>
                                                                            <dd class="col-sm-8">{{ $current_address->city??'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Area:</dt>
                                                                            <dd class="col-sm-8">{{ $current_address->area??'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">State:</dt>
                                                                            <dd class="col-sm-8">{{ $current_address->state??'' }}</dd>

                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Zip Code:</dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $current_address->zip_code??'' }}
                                                                            </dd>
                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Country:</dt>
                                                                            <dd class="col-sm-8">
                                                                                <span class="text-capitalize">{{ $current_address->country??'' }}</span>
                                                                            </dd>
                                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Phone Number:</dt>
                                                                            <dd class="col-sm-8">
                                                                                {{ $current_address->phone_number??'' }}
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-top-bank-details" role="tabpanel">
                        <div class="row">
                            <section id="profile-info">
                                <!-- Bank Details Cards -->
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <small class="card-text text-uppercase">Bank Account Details</small>
                                                    <div class="info-container">
                                                        <ul class="list-unstyled">
                                                            <li class="my-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-home"></i>
                                                                    Bank :
                                                                </span>
                                                                <span>
                                                                    @if(isset($model['user']->hasBankDetails) && !empty($model['user']->hasBankDetails->bank_name))
                                                                    {{ $model['user']->hasBankDetails->bank_name }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                            <li class="mb-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-tag"></i>
                                                                    Branch Code :</span>
                                                                <span>
                                                                    @if(isset($model['user']->hasBankDetails) && !empty($model['user']->hasBankDetails->branch_code))
                                                                    {{ $model['user']->hasBankDetails->branch_code }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                            <li class="mb-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-user"></i>
                                                                    Title :</span>
                                                                <span>
                                                                    @if(isset($model['user']->hasBankDetails) && !empty($model['user']->hasBankDetails->title))
                                                                    {{ $model['user']->hasBankDetails->title }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                            <li class="mb-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-shield"></i>
                                                                    Account Number :</span>
                                                                <span>
                                                                    @if(isset($model['user']->hasBankDetails) && !empty($model['user']->hasBankDetails->account))
                                                                    {{ $model['user']->hasBankDetails->account }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                            <li class="mb-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-shield"></i>
                                                                    IBAN Number :</span>
                                                                <span>
                                                                    @if(isset($model['user']->hasBankDetails) && !empty($model['user']->hasBankDetails->iban))
                                                                    {{ $model['user']->hasBankDetails->iban }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-top-job-history" role="tabpanel">
                        <div class="row">
                            <section id="profile-info">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="card card-action mb-4">
                                            <div class="card-header align-items-center">
                                                <small class="card-text text-uppercase">Job History Timeline</small>
                                            </div>
                                            <div class="card-body pb-0">
                                                <ul class="timeline mb-0 px-2">
                                                    @foreach ($model['histories'] as $history)
                                                        <li class="timeline-item timeline-item-transparent">
                                                            <span class="timeline-point timeline-point-primary"></span>
                                                            <div class="timeline-event">
                                                                <div class="timeline-header">
                                                                    <h6 class="mb-0">
                                                                        @if(isset($history->jobHistory->designation) && !empty($history->jobHistory->designation->title))
                                                                        {{ $history->jobHistory->designation->title }}
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    </h6>
                                                                    <small class="text-muted">
                                                                        {{ date('d M Y', strtotime($history->effective_date)) }}

                                                                        @if(isset($history->jobHistory->end_date) && !empty($history->jobHistory->end_date))
                                                                            - {{ date('d M Y', strtotime($history->jobHistory->end_date)) }}
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="ms-1">
                                                                        <h6 class="mb-0">PKR. {{ number_format($history->salary, 2) }}</h6>
                                                                        <span>
                                                                            {{ date('d M Y', strtotime($history->effective_date)) }}

                                                                            @if(isset($history->end_date) && !empty($history->end_date))
                                                                                - {{ date('d M Y', strtotime($history->end_date)) }}
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-top-cnic" role="tabpanel">
                        <div class="row">
                            <section id="profile-info">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <small class="card-text text-uppercase">CNIC FRONT & BACK SIDE</small>
                                                    <div class="info-container">
                                                        <ul class="list-unstyled">
                                                            <li class="my-3 d-flex gap-4">
                                                                @if(isset($model['user']->profile) && !empty($model['user']->profile->cnic_front))
                                                                    <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model['user']->profile->cnic_front }}" alt="CNIC Front" class="rounded img-fluid w-20 img-cnic">
                                                                @else
                                                                    <img src="{{ asset('public/admin/assets/img/cnic/front.jpg') }}" alt="No Image" class="rounded img-fluid w-20">
                                                                @endif

                                                                @if(isset($model['user']->profile) && !empty($model['user']->profile->cnic_back))
                                                                    <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model['user']->profile->cnic_back }}" alt="CNIC Back" class="rounded img-fluid w-20 img-cnic">
                                                                @else
                                                                    <img src="{{ asset('public/admin/assets/img/cnic/back.jpg') }}" alt="No Image" class="rounded img-fluid w-20">
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection