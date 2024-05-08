@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                    
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }} of
                           {{$company}} </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center row">
               
                <div class="col-md-12 mt-md-0 mt-3">
                    @php $url = URL::to('admin/company/attendance/') @endphp
                    @include('admin.layouts.employee_dropdown', [
                        'employees' => $employees,
              
                        'url' => $url,
                        'month' => $month,
                        'year' => $year,
                        'type' => 'redirect',
                        'company' => $company,
                    ])
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
@push('js')
<script src="{{ asset('public/admin/assets/js/custom-dashboard.js') }}"></script>
<script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
   

   

    function redirectPage(dropdown) {
      var selectedOption = dropdown.value;

      if (selectedOption !== '') {
        window.location.href = selectedOption;
      }
    }
  </script>
@endpush