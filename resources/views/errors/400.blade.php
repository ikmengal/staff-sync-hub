@extends('errors.layout.master')
@section('content')
    <!-- Content -->

    <!-- Error -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h2 class="mb-1 mt-4">Role Not Assigned</h2>
            <p class="mb-4 mx-2">You account doesn't have any role assigned yet,
                Please Contact Administrator.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary mb-4">Back to home</a>
            <div class="mt-4">
                <img src="{{ asset('public/admin') }}/assets/img/error/auth-register-multisteps-illustration.png" alt="page-misc-error"
                    width="225" class="img-fluid" />
            </div>
        </div>
    </div>
    <div class="container-fluid misc-bg-wrapper">
        <img src="{{ asset('public/admin') }}/assets/img/illustrations/bg-shape-image-light.png" alt="page-misc-error"
            data-app-light-img="illustrations/bg-shape-image-light.png"
            data-app-dark-img="illustrations/bg-shape-image-dark.png" />
    </div>
@endsection
<!-- /Error -->

<!-- / Content -->
