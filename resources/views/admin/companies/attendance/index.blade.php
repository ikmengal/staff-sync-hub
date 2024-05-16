@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())
@php

use Carbon\Carbon;
@endphp
@section('content')
<input type="hidden" id="current_user_slug" value="{{ !empty($user) ? $user->slug : '' }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }} of
                            month: {{ date('F, Y', mktime(0, 0, 0, $month, 1, $year)) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center row">

                <div class="col-md-4 mt-md-0 mt-3">
                    <label>Select Company</label>
                    <select class="form-control form-select select2 " data-control="select2" id="companyList">
                        <option value="">select </option>
                        @if (!empty($comapnies_list))
                        @foreach ($comapnies_list as $index => $companyName)
                        <option value="{{ $companyName->portalDb ?? '' }}" {{ $companyName->portalDb == $company ? 'selected' : '' }}>
                            {{ $companyName->name }}
                        </option>
                        @endforeach
                        @endif
                    </select>


                </div>
                <div class="col-md-4 mt-md-0 mt-3">
                    <label>Select Employee</label>
                    <select class="form-control form-select select2 " id="employeeList">
                        <option value="">select</option>
                        @if (request()->has('slug'))
                        @if (isset($employees) && !empty($employees))
                        @foreach ($employees as $item)
                        <option value="{{ $item->slug }}" @if (!empty($user) && $user->slug == $item->slug) selected @endif>
                            {{ $item->first_name }} {{$item->last_name}} ({{!empty($item->profile) ?  $item->profile->employment_id :"" }})
                        </option>
                        @endforeach
                        @endif
                        @endif

                    </select>
                </div>

                <div class="col-md-2 mt-md-0 mt-4">

                    <input type="text" id="month-list" data-joining-date="{{ $user_joining_date }}" data-company="{{ $company }}" data-current-month="{{ $currentMonth }}" placeholder="Select Month" class="form-control flatpickr-input mt-3" readonly>
                </div>
                <div class="col-md-2 mt-md-0 mt-3">

                    <button class="btn btn-primary mt-3" id="filterAttendance"><i class="fa-solid fa-filter"></i></button>
                </div>

            </div>
            @if (isset($user) && !empty($user))
            <div class="card-header d-flex justify-content-between align-items-center row">
                <div class="col-md-8">
                    <span class="card-title mb-0">

                        <div class="d-flex align-items-center">
                            @if (isset($user->profile) && !empty($user->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars') . '/' . $user->profile->profile, null) }}" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                            @else
                            <img src="{{ asset('public/admin') }}/default.png" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                            @endif
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="mx-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1 text-capitalize">{{ $user->first_name }}
                                            {{ $user->last_name }}
                                        </h6>
                                    </div>
                                    <small class="text-muted">
                                        @if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
                                        {{ $user->jobHistory->designation->title }}
                                        @else
                                        -
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                    </span>
                </div>

            </div>
            @endif
            {{-- <div class="card-header row"> --}}
            {{-- <div class="col-md-4">
                        <button class="btn btn-success" onclick="exportAttendance($month,$year,$company)">Export</button>
                    </div> --}}

            {{-- </div> --}}
            <div class="card-header border-top">
                <div class="table-responsive">
                    <table class="attendance-table table table-border min-w-800 body-input-checkbox">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Shift Time</th>
                                <th>Day</th>
                                <th>Punched In</th>
                                <th>Punched Out</th>
                                <th>Status</th>
                                <th>Working Hours</th>
                                @if (Auth::user()->hasRole('Admin'))
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($user) && !empty($user))
                            @for ($i = $begin; $i <= $end; $i->modify('+1 day'))
                                @php
                                $shift = getUserShift($user, $i->format('Y-m-d'), $company);
                                $day = date('D', strtotime($i->format('Y-m-d')));
                                $start_time =
                                date('Y-m-d', strtotime($i->format('Y-m-d'))) .
                                ' ' .
                                $shift->start_time;
                                $end_time =
                                date('Y-m-d', strtotime($i->format('Y-m-d'))) . ' ' . $shift->end_time;

                                $shiftEndTime = $shift->end_time;
                                $shiftEndTime = date('H:i', strtotime($shiftEndTime));
                                $carbonEndTime = Carbon::createFromFormat('H:i', $shiftEndTime);

                                if ($carbonEndTime->hour < 12) { $next=date('Y-m-d', strtotime('+1 day ' . $i->format(' Y-m-d'))); } else { $next=date('Y-m-d', strtotime($end_time)); } $reponse=getAttandanceSingleRecord( $user->id,
                                    $i->format('Y-m-d'),
                                    $next,
                                    'all',
                                    $shift,
                                    $company,
                                    );

                                    @endphp
                                    @if ($reponse != null)
                                    @php
                                    $attendance_adjustment = attendanceAdjustment(
                                    $user->id,
                                    $reponse['attendance_id'],
                                    $i->format('Y-m-d'),
                                    $company,
                                    );
                                    $checkHoliday = checkHoliday($user->id, $i->format('Y-m-d'), $company); //check it is holiday or company off
                                    @endphp
                                    @if (!empty($checkHoliday))
                                    <tr style="background: #cbf4dc;color: #28c76f;">
                                        <td class="mb-0" style="color: black;">{{ $i->format('d-m-Y') }}</td>
                                        <td class="mb-0" style="color: black;">{{ $reponse['shiftTiming'] }}
                                        </td>
                                        <td colspan="5" style="color: #28c76f;">
                                            <h4 class="mb-0" style="color: #28c76f;">
                                                {{ $checkHoliday->name }}
                                            </h4>
                                        </td>
                                    </tr>
                                    @else
                                
                                    <tr class="{{ $day }}">
                                        <td>{{ formatDate($i->format('d-m-Y')) }}</td>
                                        <td>{{ $reponse['shiftTiming'] }}</td>
                                        <td>{{$day  ?? ""}}</td>
                                        <td>
                                            @if ($day != 'Sat' && $day != 'Sun')
                                            <span class="punchedin d-block mb-2">{{ $reponse['punchIn'] }}</span>
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($day != 'Sat' && $day != 'Sun')
                                            <span class="punchedin d-block mb-2">{{ $reponse['punchOut'] }}</span>
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($day != 'Sat' && $day != 'Sun')
                                            @if (isset($attendance_adjustment) && !empty($attendance_adjustment) && $i->format('Y-m-d') <= date('Y-m-d')) @php $mark_type=$attendance_adjustment->mark_type; @endphp
                                                @if ($mark_type == 'firsthalf')
                                                @php $mark_type = 'Half Day' @endphp
                                                @endif
                                                <span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Marked as
                                                    {{ Str::ucfirst($mark_type) }}</span>
                                                @else
                                                @if ($day != 'Sat' && $day != 'Sun')
                                                {!! $reponse['label'] !!}
                                                @else
                                                {{ '-' }}
                                                @endif
                                                @endif
                                                @else
                                                {{ '-' }}
                                                @endif
                                        </td>
                                        <td>
                                            @if ($day != 'Sat' && $day != 'Sun')
                                            {{ $reponse['workingHours'] }}@else{{ '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @endfor
                                    @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Apply Leave Or Discrepency Modal -->

<!-- Apply Leave Or Discrepency Modal -->
@endsection
@push('js')
<script src="{{ asset('public/admin/assets/js/custom-dashboard.js') }}"></script>
<script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    const markAttendanceStoreRoute = "{{ route('mark_attendance.store') }}";

    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var month = urlParams.get('month');
        var year = urlParams.get('year');

        if (month && year) {
            // Create a new Date object with the specified month and year
            $('#month-list').datepicker({
                format: 'mm/yyyy',
                startView: 'year',
                minViewMode: 'months',
            }).datepicker('setDate', new Date(year, month - 1,
                1)); // Set the month (subtract 1 since months are zero-based)

            // Update the Datepicker with the new date

        }

    })

    $(function() {
        var currentMonth = $('#Slipbutton').data('current-month');

        var joiningMonthYear = $('#Slipbutton').data('joining-date');

        var selecCompany = $("#Slipbutton").data('company');
        var currentMonth = $('#month-list').data('current-month');
        var joiningMonthYear = $('#month-list').data('joining-date');


        var selectedMonth, selectedYear, employeeSlug;
        var urlParams = new URLSearchParams(window.location.search);
        var month = urlParams.get('month');
        var year = urlParams.get('year');

        var initialDate = '';
        if (month && year) {
            // Provided month and year are valid
            initialDate = new Date(year, month - 1);
        } else {
            // Use current month and year
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth();
            initialDate = new Date(currentYear, currentMonth);
        }
        $('#month-list').datepicker({
            format: 'mm/yyyy',
            startView: 'year',
            minViewMode: 'months',
            startDate: joiningMonthYear,
            endDate: currentMonth,
            defaultViewDate: initialDate

        }).on('changeMonth', function(e) {

            //  employeeSlug = $('#employee-slug option:selected').data('user-slug');


            selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
            selectedYear = e.date.getFullYear();


        });


        $("#filterAttendance").on('click', function(e) {

            var selecCompany = $("#companyList").val();

            var employeeSlug = $("#employeeList").val();
            // if (employeeSlug == undefined) {
            //     employeeSlug = $('#current_user_slug').val();
            // }

            if (employeeSlug == "") {
                alert("Please select an employee.");
                return false; // Stop further execution
            }

            if (!selectedMonth || !selectedYear) {
                // Set current month and year if not selected

                if (!month && !year) {
                    var currentDate = new Date();
                    selectedMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
                    selectedYear = currentDate.getFullYear();

                } else {
                    selectedMonth = month;
                    selectedYear = year;
                }
            }

            var selectOptionUrl = "{{ URL::to('admin/company/attendance/') }}/" + selecCompany +
                "?month=" +
                selectedMonth + "&year=" + selectedYear + "&slug=" + employeeSlug;

            window.location.href = selectOptionUrl;
        })

        $("#companyList").on("change", function(e) {

            var company = $(this).val();

            $.ajax({
                type: "get",
                url: "{{ route('admin.get.company.employees') }}",
                data: {
                    company: company
                },

                success: function(res) {
                    console.log(res)
                    if (res.success == true) {
                        var employee_list = $("#employeeList");
                        var employees = res.data;
                        employee_list.empty();


                        employee_list.append('<option value="">select</>')
                        employees.forEach(function(employee) {

                            employee_list.append('<option value="' + employee.slug +
                                '" >' +
                                employee.first_name + ' ' + employee.last_name +
                                ' (' + employee.profile.employment_id +
                                ') </option>');
                        });

                    } else {
                        var employee_list = $("#employeeList");
                        employee_list.empty();
                        employee_list.append('<option value="">No Employee Found</>')
                    }
                }
            });

        })




        const url = new URL(window.location.href);
        const pathname = url.pathname;
        const pathParts = pathname.split('/');
        if (pathParts.length > 6) {
            const emp = pathParts.pop();
            const year = pathParts.pop();
            const month = pathParts.pop();

            $('#Slipbutton').datepicker('setDate', new Date(year, month - 1));
        } else {
            // Get the current date and time in Pakistan time
            var currentDate = new Date();
            var currentDay = currentDate.getDate();
            var currentHour = currentDate.getUTCHours() + 5; // Add 5 hours for Pakistan time adjustment

            // Check if the current date is on or after the 26th and time is 11:00 AM or later
            if (currentDay >= 26 && currentHour >= 11) {
                // Set the day to the 1st and increment the month by 1 to show the next month
                currentDate.setDate(1);
                currentDate.setMonth(currentDate.getMonth() + 1);
            }

            $('#Slipbutton').datepicker('setDate', currentDate);

            // Update the viewDate when the view changes (e.g., navigating to a different month)
            $(document).on('changeMonth', '.datepicker', function(e) {
                $('#Slipbutton').datepicker('setViewDate', e.date);
            });
        }
    });


    function exportAttendance(event) {

        var route = event.data('route');
        var currentDate = new Date();

        var month = event.data('month');
        var year = event.data('year');
        var slug = event.data('slug');
        var company = event.data('company');

        var formattedDate = currentDate.getFullYear() +
            ('0' + (currentDate.getMonth() + 1)).slice(-2) +
            ('0' + currentDate.getDate()).slice(-2);
        var formattedTime = ('0' + currentDate.getHours()).slice(-2) +
            ('0' + currentDate.getMinutes()).slice(-2) +
            ('0' + currentDate.getSeconds()).slice(-2);

        // Construct the dynamic file name
        var filename = 'attendance_report_' + formattedDate + '_' + formattedTime + '.csv';

        // Create a hidden anchor element
        var downloadLink = document.createElement('a');
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        // Set the href attribute to the download URL
        downloadLink.href = route + "?company=" + company + "?month=" + month + "?year=" + year + "?slug=" + slug;
        // Set the download attribute to force download
        downloadLink.setAttribute('download', filename);
        // Trigger a click event on the anchor element
        downloadLink.click();
        // Clean up the anchor element
        document.body.removeChild(downloadLink);

    }

    function extractQueryParameters(queryString) {
        var queryParams = {};
        var pairs = queryString.slice(1).split('&');
        pairs.forEach(function(pair) {
            pair = pair.split('=');
            queryParams[pair[0]] = decodeURIComponent(pair[1] || '');
        });
        return queryParams;
    }


    function redirectPage(dropdown) {
        var selectedOption = dropdown.value;

        if (selectedOption !== '') {
            window.location.href = selectedOption;
        }
    }
</script>
@endpush