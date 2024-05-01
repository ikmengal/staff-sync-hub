@extends('admin.layouts.app')
@section('title', $data['title'].' | '.appName())
@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar d-flex flex-stack py-4 py-lg-8">
        <!--begin::Toolbar wrapper-->
        <div class="d-flex flex-grow-1 flex-stack flex-wrap gap-2 mb-n10" id="kt_toolbar">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Account Overview</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Profile</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-900">Overview</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar wrapper-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Navbar-->
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <!--begin: Pic-->
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            @if (!empty(getUserData($model)->profile))
                                <img src="{{ asset('public/admin/assets/img/avatars').'/'.getUserData($model)->profile }}" alt="User Avatar" />
                            @else
                                <img src="{{ asset('public/admin') }}/assets/media/svg/avatars/blank.svg" alt="User Avatar" />
                            @endif
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ getUserData($model)->name }}</a>
                                </div>
                                <!--end::Name-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <a href="#" title="Role" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <i class="ki-outline ki-profile-circle fs-4 me-1"></i>{{ getUserData($model)->role }}</a>
                                    <a href="#" title="Designation" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <i class="ki-outline ki-geolocation fs-4 me-1"></i>{{ getUserData($model)->designation }}</a>
                                    <a href="#" title="Email" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                        <i class="ki-outline ki-sms fs-4"></i>{{ getUserData($model)->email }}</a>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
                <!--begin::Navs-->
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 active">Settings</a>
                    </li>
                    <!--end::Nav item-->
                </ul>
                <!--begin::Navs-->
            </div>
        </div>
        <!--end::Navbar-->
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Profile Details</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_account_profile_details_form" class="form" action="{{ route('profile.update', $model->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PATCH') }}
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Image input-->
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('public/admin') }}/assets/media/svg/avatars/blank.svg')">
                                    <!--begin::Preview existing avatar-->
                                    @if(!empty(getUserData($model)->profile))
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('public/admin/assets/img/avatars') }}/{{ getUserData($model)->profile }}')"></div>
                                    @else
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('public/admin') }}/assets/media/svg/avatars/blank.svg')"></div>
                                    @endif
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                        <i class="ki-outline ki-pencil fs-7"></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="profile"  />
                                        <input type="hidden" name="avatar_remove" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Cancel-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                            <i class="ki-outline ki-cross fs-2"></i>
                                        </span>
                                    <!--end::Cancel-->
                                    <!--begin::Remove-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                            <i class="ki-outline ki-cross fs-2"></i>
                                        </span>
                                    <!--end::Remove-->
                                </div>
                                <!--end::Image input-->
                                <!--begin::Hint-->
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                <!--end::Hint-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <input type="text" name="first_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="First name" value="{{ $model->first_name }}" />
                                        <span id="first_name_error" class="text-danger error">{{ $errors->first('first_name') }}</span>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <input type="text" name="last_name" class="form-control form-control-lg form-control-solid" placeholder="Last name" value="{{ $model->last_name }}" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Gender</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <div class="mb-3">
                                    <div class="form-check form-check-inline mt-3">
                                        <input type="radio" id="gender-male" name="gender" class="form-check-input" @if (isset($model->profile) && !empty($model->profile->gender) && $model->profile->gender == 'male') checked @endif required
                                        value="male" />
                                        <label class="form-check-label" for="gender-male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-3">
                                        <input type="radio" id="gender-female" name="gender" class="form-check-input" @if (isset($model->profile) && !empty($model->profile->gender) && $model->profile->gender == 'female') checked @endif required
                                        value="female" />
                                        <label class="form-check-label" for="gender-female">Female</label>
                                    </div>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="gender_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Mobile</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <input type="text" class="form-control form-control-lg form-control-solid mobileNumber" id="phone_number" placeholder="Enter your phone number" value="{{ $model->profile->phone_number ?? '' }}" name="phone_number" />
                                <span id="phone_number_error" class="text-danger error"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Date of birth</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <input type="date" class="form-control form-control-lg form-control-solid" value="{{ $model->profile->date_of_birth ?? '' }}" name="date_of_birth" />
                                <span id="date_of_birth_error" class="text-danger error"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Marital Status</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <select name="marital_status" id="marital_status" class="form-control">
                                    <option value="" selected>Select marital status</option>
                                    <option value="1" {{ isset($model->profile->marital_status) && $model->profile->marital_status == 1 ? 'selected' : '' }}>
                                        Married</option>
                                    <option value="0" {{ isset($model->profile->marital_status) && $model->profile->marital_status == 0 ? 'selected' : '' }}>
                                        Single</option>
                                </select>
                                <span id="marital_status_error" class="text-danger error"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">CNIC</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <input type="text" class="form-control form-control-lg form-control-solid cnic_number" value="{{ $model->profile->cnic }}" id="cnic_number" placeholder="Enter cnic number" name="cnic" />
                                <span id="cnic_error" class="text-danger error"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">About</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <textarea name="about_me" id="about_me" cols="30" rows="5" class="form-control form-control-lg form-control-solid" placeholder="Enter about you.">{{ $model->profile->about_me ?? '' }}</textarea>
                                <span id="about_me_error" class="text-danger error"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
                <!--begin::Sign-in Method-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Sign-in Method</h3>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Content-->
                    <div id="kt_account_settings_signin_method" class="collapse show">
                        <!--begin::Card body-->
                        <div class="card-body border-top p-9">
                            <!--begin::Password-->
                            <div class="d-flex flex-wrap align-items-center mb-10">
                                <!--begin::Edit-->
                                <div id="kt_signin_password_edit" class="flex-row-fluid">
                                    <!--begin::Form-->
                                    <form id="kt_signin_change_password" class="form" novalidate="novalidate" action="{{ route('profile.change-password') }}" method="POST">
                                        @csrf

                                        <div class="row mb-1">
                                            <div class="col-lg-4">
                                                <div class="fv-row mb-0">
                                                    <label for="currentpassword" class="form-label fs-6 fw-bold mb-3">Current Password</label>
                                                    <input type="password" value="{{ old('old_password') }}" class="form-control form-control-lg form-control-solid" name="old_password" id="currentpassword" placeholder="Enter current password" />
                                                    <span id="old_password_error" class="text-danger error">{{ $errors->first('old_password') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="fv-row mb-0">
                                                    <label for="newpassword" class="form-label fs-6 fw-bold mb-3">New Password</label>
                                                    <input type="password" value="{{ old('password') }}" class="form-control form-control-lg form-control-solid" name="password" id="newpassword" placeholder="Enter new password" />
                                                    <span id="password_error" class="text-danger error">{{ $errors->first('password') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="fv-row mb-0">
                                                    <label for="confirmpassword" class="form-label fs-6 fw-bold mb-3">Confirm New Password</label>
                                                    <input type="password" value="{{ old('password_confirmation') }}" class="form-control form-control-lg form-control-solid" name="password_confirmation" id="confirmpassword" placeholder="Enter confirm new password" />
                                                    <span id="password_confirmation_error" class="text-danger error">{{ $errors->first('password_confirmation') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-primary me-2 px-6">Update Password</button>
                                        </div>
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Edit-->
                            </div>
                            <!--end::Password-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Sign-in Method-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->
    </div>
    <!--end::Content-->
@endsection
