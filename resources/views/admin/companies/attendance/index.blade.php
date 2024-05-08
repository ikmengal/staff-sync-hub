@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())
@php
    use App\Http\Controllers\Admin\AdminController;
    use Carbon\Carbon;
@endphp
@section('content')
    <input type="hidden" id="current_user_slug" value="{{ $user->slug }}">
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

                <div class="card-header d-flex justify-content-between">
                    @php
                        $navDate = $year . '-' . $month . '-01';
                        $prevMonth = strtotime($navDate . ' -1 month');
                        $nextMonth = strtotime($navDate . ' +1 month');
                    @endphp

                    <div class="d-flex align-items-center justify-content-end">
                        <button class="btn btn-primary waves-effect waves-light"
                            data-joining-date="{{ $user_joining_date }}" data-current-month="{{ $currentMonth }}"
                            id="Slipbutton">Select Month<i class="ti ti-chevron-down ms-2"></i></button>
                    </div>
                </div>
                <div class="card-header d-flex justify-content-between align-items-center row">
                    <div class="col-md-8">
                        <span class="card-title mb-0">
                            <a href="{{ route('employees.show', $user->slug) }}" class="text-body text-truncate">
                                <div class="d-flex align-items-center">
                                    @if (isset($user->profile) && !empty($user->profile->profile))
                                        <img src="{{ resize(asset('public/admin/assets/img/avatars') . '/' . $user->profile->profile, null) }}"
                                            style="width:40px !important; height:40px !important" alt
                                            class="h-auto rounded-circle" />
                                    @else
                                        <img src="{{ asset('public/admin') }}/default.png"
                                            style="width:40px !important; height:40px !important" alt
                                            class="h-auto rounded-circle" />
                                    @endif
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="mx-3">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0 me-1 text-capitalize">{{ $user->first_name }}
                                                    {{ $user->last_name }}</h6>
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
                            </a>
                        </span>
                    </div>
                    <div class="col-md-4 mt-md-0 mt-3">
                        @php $url = URL::to('admin/company/attendance/summary/') @endphp
                        @include('admin.layouts.employee_dropdown', [
                            'employees' => $employees,
                            'user' => $user,
                            'url' => $url,
                            'month' => $month,
                            'year' => $year,
                            'type' => 'redirect',
                            'company' => $company,
                        ])
                    </div>
                </div>
                <div class="card-header border-top">




                    <div class="table-responsive">

                        <table class="attendance-table table table-border min-w-800 body-input-checkbox">
                            <thead>
                                <!-- Bluck Adjustment -->

                                <!-- Bluck Adjustment -->
                                <tr>
                                    <!-- Bluck Adjustment -->
                                    {{-- <th>
                                        <div class="form-check align-items-center">
                                            <input class="form-check-input ms-0" type="checkbox" value=""
                                                id="selectAll">
                                        </div>
                                    </th> --}}
                                    <!-- Bluck Adjustment -->
                                    <th>Date</th>
                                    <th>Shift Time</th>
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
                                @php
                                     //$begin = new DateTime($year . '-' . ((int) $month - 1) . '-26');
                                     
                                    $adjustedMonth = (int) $month - 1;
                                    $adjustedYear = $year;

                                    if ($adjustedMonth == 0) {
                                        // If the adjusted month is 0 (December), decrement the year
                                        $adjustedMonth = 12; // Set to December
                                        $adjustedYear--; // Decrement the year
                                    }

                                     $begin = new DateTime($adjustedYear . '-' . $adjustedMonth . '-26');
    
                                    $beginDate = Carbon::parse($begin);
                                    $start_date = '';
                                    // if((isset($user->employeeStatus->start_date) && !empty($user->employeeStatus->start_date))){
                                    //     $start_date = $user->employeeStatus->start_date;
                                    //     $start_date = Carbon::parse($start_date);
                                    // }
                                    if (getUserJoiningDate($user)) {
                                        $start_date = getUserJoiningDate($user);
                                    }
                                    $end = new DateTime($year . '-' . (int) $month . '-25');
                                @endphp
                                @for ($i = $begin; $i <= $end; $i->modify('+1 day'))
                                    @php
                                        $shift = getUserShift($user, $i->format('Y-m-d'), $company);
                                        $day = date('D', strtotime($i->format('Y-m-d')));
                                        $start_time =
                                            date('Y-m-d', strtotime($i->format('Y-m-d'))) . ' ' . $shift->start_time;
                                        $end_time =
                                            date('Y-m-d', strtotime($i->format('Y-m-d'))) . ' ' . $shift->end_time;

                                        $shiftEndTime = $shift->end_time;
                                        $shiftEndTime = date('H:i', strtotime($shiftEndTime));
                                        $carbonEndTime = Carbon::createFromFormat('H:i', $shiftEndTime);

                                        if ($carbonEndTime->hour < 12) {
                                            $next = date('Y-m-d', strtotime('+1 day ' . $i->format('Y-m-d')));
                                        } else {
                                            $next = date('Y-m-d', strtotime($end_time));
                                        }
                                        $reponse = AdminController::getAttandanceSingleRecord(
                                            $user->id,
                                            $i->format('Y-m-d'),
                                            $next,
                                            'all',
                                            $shift,
                                            $company,
                                        );
                                      
                                    @endphp


                                    @if ($reponse != null)
                                        @php
                                            $applied = userAppliedLeaveOrDiscrepency(
                                                $user->id,
                                                $reponse['type'],
                                                $i->format('Y-m-d'),
                                                $company,
                                            );
                                            $attendance_adjustment = '';
                                        @endphp

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
                                                <td class="mb-0" style="color: black;">{{ $reponse['shiftTiming'] }}</td>
                                                <td colspan="5" style="color: #28c76f;">
                                                    <h4 class="mb-0" style="color: #28c76f;">{{ $checkHoliday->name }}
                                                    </h4>
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="{{ $day }}">
                                                <!-- Bluck Adjustment -->
                                                {{-- <td>
                                                    @if ($day != 'Sat' && $day != 'Sun')
                                                        @php $adjustment_date = $i->format("Y-m-d"); @endphp
                                                        @if (isset($attendance_adjustment) && !empty($attendance_adjustment))
                                                            <input class="form-check-input" type="checkbox" disabled
                                                                checked>
                                                        @else
                                                            <input class="form-check-input body-checkbox" type="checkbox"
                                                                value="{{ $adjustment_date }}" id="checkbox">
                                                        @endif
                                                    @endif
                                                </td> --}}
                                                <!-- Bluck Adjustment -->
                                                <td>{{ $i->format('d-m-Y') }}</td>
                        
                                                <td>{{ $reponse['shiftTiming'] }}</td>
                                                <td>
                                                 

                                                    @if ($day != 'Sat' && $day != 'Sun')

                                                        <span
                                                            class="punchedin d-block mb-2">{{ $reponse['punchIn'] }}</span>
                                                      
                                                    @else
                                                        {{ '-' }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($day != 'Sat' && $day != 'Sun')
                                                        <span
                                                            class="punchedin d-block mb-2">{{ $reponse['punchOut'] }}</span>
                                                    
                                                    @else
                                                        {{ '-' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($day != 'Sat' && $day != 'Sun')
                                                        @if (isset($attendance_adjustment) && !empty($attendance_adjustment) && $i->format('Y-m-d') <= date('Y-m-d'))
                                                            @php $mark_type = $attendance_adjustment->mark_type; @endphp
                                                            @if ($mark_type == 'firsthalf')
                                                                @php $mark_type = 'Half Day' @endphp
                                                            @endif
                                                            <span class="badge bg-label-danger"><i
                                                                    class="far fa-dot-circle text-danger"></i> Marked as
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Apply Leave Or Discrepency Modal -->
    <div class="modal fade" id="discrepency-or-leave-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>

                    <form id="create-form" class="row g-3" data-method="POST"
                        data-modal-id="discrepency-or-leave-modal">
                        @csrf

                        <input type="hidden" name="user_slug" id="user-slug">
                        <input type="hidden" name="type" id="applied-type">
                        <input type="hidden" name="date" id="applied-date">
                        <div class="mb-3" id="leave_types_div">
                            <label class="form-label" for="leave_type_id">Leave Type</label>
                            <div class="position-relative">
                                <select id="leave_type_id" name="leave_type_id" class="form-control">
                                    <option value="">Select type</option>
                                    @foreach ($leave_types as $leave_type)
                                        <option value="{{ $leave_type->id }}">{{ $leave_type->name }}</option>
                                    @endforeach
                                </select>
                                <span id="leave_type_id_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="reason_id">Reason <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <textarea class="form-control" rows="5" name="reason" id="reason_id" placeholder="Enter reason"></textarea>
                                <span id="reason_error" class="text-danger error">{{ $errors->first('reason') }}</span>
                            </div>
                        </div>

                        <div class="col-12 mt-3 action-btn">
                            <div class="demo-inline-spacing sub-btn">
                                <button type="submit"
                                    class="btn btn-primary me-sm-3 me-1 applyDiscrepancyLeaveBtn">Submit</button>
                                <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                            <div class="demo-inline-spacing loading-btn" style="display: none;">
                                <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                                    <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                                <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Apply Leave Or Discrepency Modal -->
@endsection
@push('js')
    <script src="{{ asset('public/admin/assets/js/custom-dashboard.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script>
        const markAttendanceStoreRoute = '{{ route('mark_attendance.store') }}';

        $(document).on('click', '#selectAll', function() {
            // Check/uncheck all checkboxes based on the Select All checkbox
            $(this).parents('.body-input-checkbox').find(".body-checkbox").prop("checked", $(this).prop("checked"));

            var total_checked_length = $(this).parents('.body-input-checkbox').find(".body-checkbox:checked")
            .length;

            if (total_checked_length > 0) {
                $(this).parents('.body-input-checkbox').find(".bluk-adjustment-btn").removeClass("d-none");
            } else {
                $(this).parents('.body-input-checkbox').find(".bluk-adjustment-btn").addClass("d-none");
            }
        });
        // Individual checkbox click event
        $(document).on('click', ".body-checkbox", function() {
            // Check the Select All checkbox if all checkboxes are checked
            var total_checkboxes_length = $(this).parents('.body-input-checkbox').find(".body-checkbox").length;
            var total_checked_length = $(this).parents('.body-input-checkbox').find(".body-checkbox:checked")
            .length;

            if (total_checked_length > 0 && total_checked_length < total_checkboxes_length) {
                $(this).parents('.body-input-checkbox').find("#selectAll").prop("checked", false);
                $(this).parents('.body-input-checkbox').find(".bluk-adjustment-btn").removeClass("d-none");
            } else if (total_checked_length === total_checkboxes_length) {
                $(this).parents('.body-input-checkbox').find("#selectAll").prop("checked", true);
                $(this).parents('.body-input-checkbox').find(".bluk-adjustment-btn").addClass("d-none");
            } else {
                $(this).parents('.body-input-checkbox').find("#selectAll").prop("checked", false);
                $(this).parents('.body-input-checkbox').find(".bluk-adjustment-btn").addClass("d-none");
            }
        });
        //Bluck Adjustments code

        $(document).on('click', '#custom-add-btn', function() {
            var leave_types = $(this).data('leave-types');
            var user_slug = $(this).attr('data-user');
            var type = $(this).attr('data-type');
            var date = $(this).attr('data-date');

            var targeted_modal = $(this).attr('data-bs-target');

            var url = $(this).attr('data-url');
            var modal_label = $(this).attr('title');

            $('#user-slug').val(user_slug);
            $('#applied-date').val(date);
            $('#applied-type').val(type);

            $(targeted_modal).find('#modal-label').html(modal_label);
            $(targeted_modal).find("#create-form").attr("action", url);

            var html = '';
            if (type == 'lateIn' || type == 'earlyout') {
                $('#leave_types_div').hide();
            } else if (type == 'firsthalf' || type == 'lasthalf') {
                $('#leave_types_div').show();
                $.each(leave_types, function(index, val) {
                    if (val.name == 'Half-Day') {
                        html += '<option value="' + val.id + '" selected>' + val.name + '</option>';
                    }
                });
            } else {
                $('#leave_types_div').show();
                $.each(leave_types, function(index, val) {
                    if (val.name != 'Half-Day') {
                        html += '<option value="' + val.id + '">' + val.name + '</option>';
                    }
                });
            }
            $('#leave_type_id').html(html);
        });

        $(function() {
            var currentMonth = $('#Slipbutton').data('current-month');

            var joiningMonthYear = $('#Slipbutton').data('joining-date');
            $('#Slipbutton').datepicker({
                format: 'mm/yyyy',
                startView: 'year',
                minViewMode: 'months',
                startDate: joiningMonthYear,
                endDate: currentMonth,
            }).on('changeMonth', function(e) {
                var employeeSlug = $('#employee-slug option:selected').data('user-slug');
                if (employeeSlug == undefined) {
                    employeeSlug = $('#current_user_slug').val();
                }
                var selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
                var selectedYear = e.date.getFullYear();

                var selectOptionUrl = "{{ URL::to('user/attendance/summary') }}/" + selectedMonth + "/" +
                    selectedYear + "/" + employeeSlug;

                window.location.href = selectOptionUrl;
            });
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

        function redirectPage(dropdown) {
            var selectedOption = dropdown.value;

            if (selectedOption !== '') {
                window.location.href = selectedOption;
            }
        }
    </script>
@endpush
