@extends('admin.auth.layouts.app')
@section('title', $title, ' - '. appName())
@section('content')
<h4 class="mb-1 pt-2">Forgot Password? 🔒</h4>
    <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
              type="email"
              class="form-control"
              id="email"
              name="email"
              placeholder="Enter your email"
              autofocus
            />
            <span class="text-danger">{{ $errors->first('email') }}</span>
        </div>
        <button type="submit" class="btn btn-primary d-grid w-100">Send Reset Link</button>
    </form>
    <div class="text-center">
        <a href="{{ URL::previous() }}" class="d-flex align-items-center justify-content-center">
            <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
            Back to login
        </a>
    </div>
@endsection
