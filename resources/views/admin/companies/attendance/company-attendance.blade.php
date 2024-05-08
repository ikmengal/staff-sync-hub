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
                                {{ $company }} </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center row">

                    <div class="col-md-4 mt-md-0 mt-3">
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
                    <div class="col-md-4 mt-md-0 mt-3">

                        <input type="text" id="month-list" data-joining-date="{{ $user_joining_date }}"
                            data-company="{{ $company }}" data-current-month="{{ $currentMonth }}"
                            placeholder="Select Month" class="form-control flatpickr-input" readonly>
                    </div>
                    <div class="col-md-4 mt-md-0">

                        <button class="btn btn-primary " id="filterAttendance"><i class="fa-solid fa-filter"></i></button>
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
        var currentMonth = $('#month-list').data('current-month');
        var joiningMonthYear = $('#month-list').data('joining-date');


        var selectedMonth, selectedYear, employeeSlug;
        $('#month-list').datepicker({
            format: 'mm/yyyy',
            startView: 'year',
            minViewMode: 'months',
            endDate: currentMonth

        }).on('changeMonth', function(e) {

            //  employeeSlug = $('#employee-slug option:selected').data('user-slug');


            selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
            selectedYear = e.date.getFullYear();



        });


        $("#filterAttendance").on('click', function(e) {

            var selecCompany = $("#month-list").data('company');
            var employeeSlug = $("#redirectDropdown").val();
            // if (employeeSlug == undefined) {
            //     employeeSlug = $('#current_user_slug').val();
            // }
       
            if (employeeSlug == "") {
                alert("Please select an employee.");
                return false; // Stop further execution
            }

            if (!selectedMonth || !selectedYear) {
                // Set current month and year if not selected
                var currentDate = new Date();
                selectedMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
                selectedYear = currentDate.getFullYear();
            }
            var selectOptionUrl = "{{ URL::to('admin/company/attendance/') }}/" + selecCompany + "?month=" +
                selectedMonth + "&year=" + selectedYear + "&slug=" + employeeSlug;

            window.location.href = selectOptionUrl;
        })

        function redirectPage(dropdown) {
            var selectedOption = dropdown.value;

            if (selectedOption !== '') {
                window.location.href = selectedOption;
            }
        }
    </script>
@endpush
