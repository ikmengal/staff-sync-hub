@extends('admin.auth.layouts.app')
@section('title', $title. ' - '. appName())
@section('content')
    {{-- <h4 class="mb-1 pt-2">Welcome to @if(isset(settings()->name) && !empty(settings()->name)) {{ settings()->name }} @endif! 👋</h4> --}}
    <h4 class="mb-1 pt-2">Welcome to Cyberonix Consulting Limited 👋</h4>
    <p class="mb-4">Please sign-in to your account and start the adventure</p>
      <span id="errorMessage"></span>
    <form id="loginForm" action="{{ route('admin.login') }}" method="POST">
        @csrf
        <input type="hidden" name="secretKey" id="secretKey">
        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="email" @if(isset($_COOKIE["email"])) value="{{ $_COOKIE["email"] }}" @endif name="email" placeholder="Enter your email" autofocus />
            <span class="text-danger">{{ $errors->first('email') }}</span>
        </div>
        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                <a href="{{ route('password.request') }}">
                    <small>Forgot Password?</small>
                </a>
            </div>
            <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" @if(isset($_COOKIE["password"])) value="{{ $_COOKIE["password"] }}" @endif name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                <span class="text-danger">{{ $errors->first('password') }}</span>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" @if(isset($_COOKIE["email"])) checked @endif >
                <label class="form-check-label" for="remember"> Remember Me </label>
            </div>
        </div>
        <div class="col-12 mt-3">
            <span id="login-btn">
                <button type="submit" id="loginButton" class="btn btn-primary d-grid w-100">Sign in </button>
            </span>

            <div id="loader" style="display: none;">
                <button type="button" class="btn btn-primary w-100" disabled><span class="spinner-border me-1" role="status" aria-hidden="true"></span>Loading...</button>
            </div>
        </div>
    </form>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $("#loginButton").click(function(e) {
            e.preventDefault(); // Prevent the default form submission
            var email = $("#email").val();
            var pass = $("#password").val();
            if (email && pass) {
                // Show the loader
                $('#login-btn').hide();
                $("#loader").show();
                // Hide the error message if it's currently displayed
                $("#errorMessage").hide();

                var url = $(this).attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#loginForm").serialize(), // Serialize form data
                    success: function(response) {
                        // Check the response for validation errors
                        if (response.success == true) {
                            // If login is successful, you can redirect or perform other actions
                            window.location.href = response.route;
                        } else {
                            // If there are validation errors, hide the loader and display the error message
                            $("#loader").hide();
                            $('#login-btn').show();
                            var html = '<div class="alert alert-danger">' + response.error + '</div>';
                            $("#errorMessage").html(html).show();
                        }
                    },
                    error: function() {
                        // Handle AJAX errors here
                        $("#loader").hide();
                        $('#login-btn').show();
                        var html = '<div class="alert alert-danger">Invalid login credentials</div>';
                        $("#errorMessage").html(html).show();
                    }
                });
            } else {
                var html = '<div class="alert alert-danger">Please insert email and password</div>';
                $("#errorMessage").html(html).show();
            }
        });
    });
</script>
@endpush
