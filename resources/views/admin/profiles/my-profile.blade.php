@extends('admin.layouts.app')
@section('title', $data['title'] . ' | ' . appName())
@section('content')
    <!--begin::Toolbar-->

    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User Profile /</span> Profile</h4>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
            </li>
        
            <li class="breadcrumb-item text-muted">Profile</li>

            <li class="breadcrumb-item text-gray-900">Overview</li>
            <!--end::Item-->
        </ul>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        <img src="{{ asset('public/admin') }}/assets/img/pages/profile-banner.png" alt="Banner image"
                            class="rounded-top" />
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            @if(loginUser(Auth::user()) && !empty(loginUser(Auth::user())->profile))
                                <img src="{{ asset('public/admin/assets/img/avatars').'/'.loginUser(Auth::user())->profile }}"
                                    alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150px" />
                            @else
                            <img src="{{ asset('public/admin') }}/default.png"  alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img"  width="150px" />
                            @endif
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div
                                class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>{{ getUserData($model)->name }}</h4>
                                    <ul
                                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        <li class="list-inline-item"><i
                                                class="ti ti-color-swatch"></i>{{ getUserData($model)->role }}</li>
                                        <li class="list-inline-item"><i
                                                class="ti ti-map-pin"></i>{{ getUserData($model)->designation }}</li>
                                        <li class="list-inline-item"><i class="ti ti-calendar"></i>
                                            {{ getUserData($model)->email }}</li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card card-action mb-4">
                    <div class="card-header align-items-center">
                        <h5 class="card-action-title mb-0">Profile Detail</h5>
                     
                      </div>
                      <div class="card-body pb-0">
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
                                            {{-- <!--begin::Label-->
                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                <i class="ki-outline ki-pencil fs-7"></i>
                                                <!--begin::Inputs-->
                                                <input type="file" name="profile"  />
                                                <input type="hidden" name="avatar_remove" />
                                                <!--end::Inputs-->
                                            </label> --}}
                                            <div class="mb-3">
                                            
                                                <input class="form-control" type="file" id="profile" name="profile">
                                                <input type="hidden" name="avatar_remove" />
                                              </div>
                                            <!--end::Label-->
                                            <!--begin::Cancel-->
                                            {{-- <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                </span> --}}
                                            <!--end::Cancel-->
                                            <!--begin::Remove-->
                                            {{-- <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                </span> --}}
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
                                <div class="row mb-4">
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
                                <div class="row mb-4">
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
                                <div class="row mb-4">
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
                                <div class="row mb-4">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Date of birth</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="date" class="form-control form-control-lg form-control-solid" value="{{ !empty($model->profile) ? $model->profile->date_of_birth : '' }}" name="date_of_birth" />
                                        <span id="date_of_birth_error" class="text-danger error"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-4">
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
                                <div class="row mb-4">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">CNIC</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" class="form-control form-control-lg form-control-solid cnic_number" value="{{!empty($model->profile) ? $model->profile->cnic : "" }}" id="cnic_number" placeholder="Enter cnic number" name="cnic" />
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
                                        <textarea name="about_me" id="about_me" cols="30" rows="5" class="form-control form-control-lg form-control-solid" placeholder="Enter about you.">{{ !empty($model->profile) ? $model->profile->about_me : '' }}</textarea>
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
                      </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card card-action mb-4">
                    <div class="card-header align-items-center">
                        <h5 class="card-action-title mb-0">Sign-in Method</h5>
                     
                      </div>
                      <div class="card-body pb-0">
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
                            <div  class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type="submit" class="btn btn-primary me-2 px-6">Update Password</button>
                            </div>
                        </form>
                      </div>

                </div>
            </div>
        </div>
    </div>

    <!--end::Toolbar-->
    <!--begin::Content-->

    <!--end::Content-->
@endsection
