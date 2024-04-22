@extends('admin.auth.layouts.app')
@section('title', $title, ' - '. appName())
@section('content')
    <h3 class="mb-1 fw-bold">Reset Password 🔒</h3>
    <p class="mb-4">for <span class="fw-bold">{{ $request->email }}</span></p>
    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="hidden" name="email" value="{{ $request->email }}">

        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password">New Password</label>
            <div class="input-group input-group-merge">
            <input
                type="password"
                id="password"
                class="form-control"
                name="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password"
            />
            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
        </div>
        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <div class="input-group input-group-merge">
            <input
                type="password"
                id="password_confirmation"
                class="form-control"
                name="password_confirmation"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password"
            />
            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary d-grid w-100 mb-3">Set new password</button>
        <div class="text-center">
            <a href="{{ route('admin.login') }}">
            <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
            Back to login
            </a>
        </div>
    </form>
@endsection
