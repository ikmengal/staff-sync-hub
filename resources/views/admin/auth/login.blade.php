@extends('admin.auth.layouts.app')

@section('content')
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('admin.login') }}" data-kt-redirect-url="{{ route('dashboard') }}" method="POST">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-11">
                <!--begin::Title-->
                <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
                <!--end::Title-->
                <!--begin::Subtitle-->
                <div class="text-gray-500 fw-semibold fs-6">
                    <h3 class="mb-1 fw-bold">Welcome to @if(isset(settings()->name) && !empty(settings()->name)) {{ settings()->name }} @endif! 👋</h3>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>
                </div>
                <!--end::Subtitle=-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group=-->
            <div class="fv-row mb-8">
                <!--begin::Email-->
                <input type="text" @if(isset($_COOKIE["email"])) value="{{ $_COOKIE["email"] }}" @endif name="email" placeholder="Enter your email" autofocus autocomplete="off" class="form-control bg-transparent" />
                <span class="text-danger">{{ $errors->first('email') }}</span>
                <!--end::Email-->
            </div>
            <!--end::Input group=-->
            <div class="fv-row mb-3">
                <!--begin::Password-->
                <input type="password" @if(isset($_COOKIE["password"])) value="{{ $_COOKIE["password"] }}" @endif name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" autocomplete="off" class="form-control bg-transparent" />
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                    <i class="ki-outline ki-eye-slash fs-2"></i>
                    <i class="ki-outline ki-eye fs-2 d-none"></i>
                </span>
                <span class="text-danger">{{ $errors->first('password') }}</span>
                <!--end::Password-->
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" @if(isset($_COOKIE["email"])) checked @endif >
                    <label class="form-check-label" for="remember"> Remember Me </label>
                </div>
            </div>
            <!--end::Input group=-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                <div></div>
                <!--begin::Link-->
                <a href="{{ route('password.request') }}" class="link-primary">Forgot Password ?</a>
                <!--end::Link-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Submit button-->
            <div class="d-grid mb-10">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Sign In</span>
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    <!--end::Indicator progress-->
                </button>
            </div>
            <!--end::Submit button-->
        </form>
        <!--end::Form-->
    </div>
@endsection
@push('js')

@endpush
