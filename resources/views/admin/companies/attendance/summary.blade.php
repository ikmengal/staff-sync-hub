@extends('admin.layouts.app')
@section('title', $title .' -  ' . appName())
@php
    use App\Http\Controllers\Admin\AdminController;
    use Carbon\Carbon;
@endphp

@section('content')
    <input type="hidden" id="current_user_slug" value="{{ $user->slug }}" >
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }} of month: {{ date('F, Y', mktime(0, 0, 0, $month, 1, $year)) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    @php
                        $navDate = $year.'-'.$month.'-01';
                        $prevMonth=strtotime($navDate.' -1 month');
                        $nextMonth=strtotime($navDate.' +1 month');
                    @endphp

                    <div class="d-flex align-items-center justify-content-end">
                        <button class="btn btn-primary waves-effect waves-light" data-joining-date="{{ $user_joining_date }}" data-current-month="{{ $currentMonth }}" id="Slipbutton">Select Month<i class="ti ti-chevron-down ms-2"></i></button>
                    </div>
                </div>
                <div class="card-header d-flex justify-content-between align-items-center row">
                    <div class="col-md-8">
                        <span class="card-title mb-0">
                            <a href="{{ route('employees.show', $user->slug) }}" class="text-body text-truncate">
                                <div class="d-flex align-items-center">
                                    @if(isset($user->profile) && !empty($user->profile->profile))
                                        <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$user->profile->profile, null) }}" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                                    @else
                                        <img src="{{ asset('public/admin') }}/default.png" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                                    @endif
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="mx-3">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0 me-1 text-capitalize">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                            </div>
                                            <small class="text-muted">
                                                @if(isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
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
                        @include('admin.layouts.employee_dropdown', ['employees' => $employees, 'user' => $user, 'url' => $url, 'month' => $month, 'year' => $year, 'type' => 'redirect','company'=>$company])
                    </div>
                </div>

                <div class="card-header border-top">
    
                    @php
                    $statistics = AdminController::getAttandanceCount($user->id, $monthDays->first_date , $monthDays->last_date ,'all', $shift,$company);

                    $shiftStart = date("H:i:s", strtotime('-6 hours '.$shift->start_time));
                    $shiftEnd = date("H:i:s", strtotime('+6 hours '.$shift->end_time));

                    $start_time = explode(':', $shiftStart);
                    $end_time = explode(':', $shiftEnd);

                    $last_month = date('m');
                    $last_year = date('Y');
                    if ($last_month == $month) {
                        $last_month = date('m', strtotime('-1 month'));
                        $last_year = date('m', strtotime('-1 month'));
                    }

                    if(settings()->discrepancy_leave_allow_enable_disable){
                        $isCurrentTimeInRange = true;
                    }else{
                        $currentMonthStart = Carbon::create($last_year, $last_month, 26, $start_time[0], $start_time[1], 0); // e.g: Start time 21:00:00
                        $currentMonthEnd = Carbon::create($year, $month, 26, $end_time[0], $end_time[1], 0); // e.g: End time 6:00:00 AM
                        $isCurrentTimeInRange = now()->between($currentMonthStart, $currentMonthEnd);
                    }
                @endphp
                  

                    <div class="table-responsive">
                        <table class="attendance-table table table-border min-w-800">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Shift Time</th>
                                    <th>Punched In</th>
                                    <th>Punched Out</th>
                                    <th>Status</th>
                                    <th>Working Hours</th>
                                    @if($user->id != Auth::user()->id)
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $begin = new DateTime($year."-".((int)$month-1)."-26");
                                    $beginDate = Carbon::parse($begin);
                                    $start_date = '';
                                    // if((isset($user->employeeStatus->start_date) && !empty($user->employeeStatus->start_date))){
                                    //     $start_date = $user->employeeStatus->start_date;
                                    //     $start_date = Carbon::parse($start_date);
                                    // }
                                    if(getUserJoiningDate($user)){
                                        $start_date = getUserJoiningDate($user);
                                    }
                                    $end   = new DateTime($year."-".(int)$month."-25");
                                @endphp
                                @for($i = $begin; $i <= $end; $i->modify('+1 day'))
                                    @php
                                        $shift = getUserShift($user, $i->format("Y-m-d"),$company);
                                        $day=date("D", strtotime($i->format("Y-m-d")));
                                        // $next=date("Y-m-d", strtotime('+1 day '.$i->format("Y-m-d")));
                                        $start_time = date('Y-m-d', strtotime($i->format("Y-m-d"))).' '.$shift->start_time;
                                        $end_time = date("Y-m-d", strtotime($i->format("Y-m-d"))).' '.$shift->end_time;

                                        $shiftEndTime = $shift->end_time;
                                        $shiftEndTime = date('H:i', strtotime($shiftEndTime));
                                        $carbonEndTime = Carbon::createFromFormat('H:i', $shiftEndTime);

                                        if ($carbonEndTime->hour < 12) {
                                            $next=date("Y-m-d", strtotime('+1 day '.$i->format("Y-m-d")));
                                        } else {
                                            $next=date('Y-m-d', strtotime($end_time));
                                        }

                                        $reponse = AdminController::getAttandanceSingleRecord($user->id,$i->format("Y-m-d"),$next,'all',$shift,$company);
                                    @endphp
                                    @if($reponse!=null)
                                        @php
                                            $applied = userAppliedLeaveOrDiscrepency($user->id, $reponse['type'], $i->format("Y-m-d"),$company);
                                            $attendance_adjustment = "";
                                        @endphp
                                        @php
                                            $attendance_adjustment = attendanceAdjustment($user->id, $reponse['attendance_id'], $i->format("Y-m-d"),$company);
                                            $checkHoliday = checkHoliday($user->id, $i->format("Y-m-d"),$company); //check it is holiday or company off
                                        @endphp

                                        @if(!empty($checkHoliday))
                                            <tr style="background: #cbf4dc;color: #28c76f;">
                                                <td class="mb-0" style="color: black;">{{$i->format("d-m-Y")}}</td>
                                                <td class="mb-0" style="color: black;">{{$reponse['shiftTiming']}}</td>
                                                <td colspan="5" style="color: #28c76f;">
                                                    <h4 class="mb-0" style="color: #28c76f;">{{ $checkHoliday->name }}</h4>
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="{{$day}}">
                                                <td>{{$i->format("d-m-Y")}}</td>
                                                <td>{{$reponse['shiftTiming']}}</td>
                                                <td>
                                                    @php
                                                        $currentDate = date('Y-m-d'); // Current date in 'Y-m-d' format
                                                        $midnightTimestamp = strtotime($currentDate . ' 00:00:00'); // Midnight timestamp
                                                    @endphp
                                                    @if($day!='Sat' && $day!='Sun')
                                                        <span class="punchedin d-block mb-2">{{$reponse['punchIn']}}</span>
                                                        @if($start_date <= $begin)
                                                            @if(empty($applied) && $isCurrentTimeInRange)
                                                                @if(isset($attendance_adjustment) && !empty($attendance_adjustment->mark_type) && ($i->format("Y-m-d") <= date('Y-m-d')))
                                                                    @if($attendance_adjustment->mark_type=='lateIn' || $attendance_adjustment->mark_type=='firsthalf' || $attendance_adjustment->mark_type=='absent')
                                                                        @php $type_label = ''; $apply_date='' @endphp
                                                                        @if($attendance_adjustment=='firsthalf' || $attendance_adjustment=='absent')
                                                                            @php
                                                                                $type_label = 'Apply Leave';
                                                                                $apply_date =  $i->format("Y-m-d");
                                                                            @endphp
                                                                        @else
                                                                            @php
                                                                                $type_label = 'Apply Discrepency';
                                                                                $apply_date = $i->format("Y-m-d");
                                                                            @endphp
                                                                        @endif

                                                                        <a href="#"
                                                                            class="badge bg-label-danger"
                                                                            id="custom-add-btn"
                                                                            tabindex="0" aria-controls="DataTables_Table_0"
                                                                            type="button" data-bs-toggle="modal"
                                                                            data-bs-target="#discrepency-or-leave-modal"
                                                                            data-toggle="tooltip"
                                                                            data-placement="top"
                                                                            title="{{ $type_label }}"
                                                                            data-url="{{ route('user_leaves.store') }}"
                                                                            data-user="{{ $user->slug }}"
                                                                            data-date='{{$apply_date}}'
                                                                            data-type="{{ $attendance_adjustment->mark_type }}"
                                                                            data-leave-types="{{ json_encode($leave_types) }}"
                                                                            >
                                                                            @if($attendance_adjustment->mark_type!="lateIn")
                                                                                Leave
                                                                            @else
                                                                                Discrepency
                                                                            @endif
                                                                        </a>
                                                                    @endif
                                                                @elseif(strtotime($i->format("Y-m-d")) <= $midnightTimestamp)
                                                                    @if($reponse['type']=='lateIn' || $reponse['type']=='firsthalf' || $reponse['type']=='absent')
                                                                        @php $type_label = ''; $apply_date='' @endphp
                                                                        @if($reponse['type']=='firsthalf' || $reponse['type']=='absent')
                                                                            @php
                                                                                $type_label = 'Apply Leave';
                                                                                $apply_date = $i->format("Y-m-d") ;
                                                                            @endphp
                                                                        @else
                                                                            @php
                                                                                $type_label = 'Apply Discrepency';
                                                                                $apply_date = $i->format("Y-m-d") ;
                                                                            @endphp
                                                                        @endif
                                                                        @if(isset($user->jobHistory->userEmploymentStatus) && !empty($user->jobHistory->userEmploymentStatus) && $reponse['type']=='lateIn' && checkEmployeeAllowToFillDiscrepancy($user))
                                                                            <a href="#"
                                                                                class="badge bg-label-danger"
                                                                                id="custom-add-btn"
                                                                                tabindex="0" aria-controls="DataTables_Table_0"
                                                                                type="button" data-bs-toggle="modal"
                                                                                data-bs-target="#discrepency-or-leave-modal"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="{{ $type_label }}"
                                                                                data-url="{{ route('user_leaves.store') }}"
                                                                                data-user="{{ $user->slug }}"
                                                                                data-date='{{$apply_date}}'
                                                                                data-type="{{ $reponse['type'] }}"
                                                                                data-leave-types="{{ json_encode($leave_types) }}"
                                                                                >
                                                                                Discrepency
                                                                            </a>
                                                                        @elseif($remaining_filable_leaves > 0 )
                                                                            <a href="#"
                                                                                class="badge bg-label-danger"
                                                                                id="custom-add-btn"
                                                                                tabindex="0" aria-controls="DataTables_Table_0"
                                                                                type="button" data-bs-toggle="modal"
                                                                                data-bs-target="#discrepency-or-leave-modal"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="{{ $type_label }}"
                                                                                data-url="{{ route('user_leaves.store') }}"
                                                                                data-user="{{ $user->slug }}"
                                                                                data-date='{{$apply_date}}'
                                                                                data-type="{{ $reponse['type'] }}"
                                                                                data-leave-types="{{ json_encode($leave_types) }}"
                                                                                >
                                                                                Leave
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @elseif(!empty($applied) && ($reponse['type']=='lateIn' || $reponse['type']=='firsthalf' || $reponse['type']=='absent'))
                                                                @if($applied->status==1)
                                                                    <span class="badge bg-label-success" title="Approved: {{ date('d F Y h:i A', strtotime($applied->updated_at)) }}">Approved</span>
                                                                @elseif($applied->status==2)
                                                                    <span class="badge bg-label-danger" title="Rejected: {{ date('d F Y h:i A', strtotime($applied->updated_at)) }}">Rejected</span>
                                                                @else
                                                                    <span class="badge bg-label-warning" title="Applied At: {{ date('d F Y h:i A', strtotime($applied->created_at)) }}">Pending</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @elseif(isset($user->employeeStatus->employmentStatus) && $user->employeeStatus->employmentStatus->name=='Permanent' && $beginDate->greaterThanOrEqualTo($start_date) && strtotime($i->format("Y-m-d")) < $midnightTimestamp)
                                                        @if(!empty($applied))
                                                            @if($applied->status==1)
                                                                <span class="badge bg-label-success" title="Approved: {{ date('d F Y h:i A', strtotime($applied->updated_at)) }}">Approved</span>
                                                            @elseif($applied->status==2)
                                                                <span class="badge bg-label-danger" title="Rejected: {{ date('d F Y h:i A', strtotime($applied->updated_at)) }}">Rejected</span>
                                                            @else
                                                                <span class="badge bg-label-warning" title="Applied At: {{ date('d F Y h:i A', strtotime($applied->created_at)) }}">Pending</span>
                                                            @endif
                                                        @elseif($remaining_filable_leaves > 0)
                                                            @if($i->format("Y-m-d") <= date('Y-m-d'))
                                                                @if($day=='Sat')
                                                                    @php
                                                                        $date = Carbon::createFromFormat('Y-m-d', $i->format("Y-m-d"));
                                                                        $nextDate = $date->copy()->addDays(2);
                                                                        $secondNextDate = $nextDate->copy()->addDay();
                                                                        $previousDate = $date->copy()->subDay();
                                                                    @endphp
                                                                @else
                                                                    @php
                                                                        $date = Carbon::createFromFormat('Y-m-d', $i->format("Y-m-d"));
                                                                        $nextDate = $date->copy()->addDay();
                                                                        $secondNextDate = $nextDate->copy()->addDay();

                                                                        $previousDate = $date->copy()->subDays(2);
                                                                    @endphp
                                                                @endif
                                                                @if((checkAdjustedAttendance($user->id, date('Y-m-d', strtotime($nextDate)),$company) && checkAdjustedAttendance($user->id, date('Y-m-d', strtotime($previousDate)),$company)) && checkAttendance($user->id, date('Y-m-d', strtotime($nextDate)), date('Y-m-d', strtotime($secondNextDate)), $shift,$company) && checkAttendance($user->id, date('Y-m-d', strtotime($previousDate)), $i->format("Y-m-d"), $shift,$company))
                                                                    <a href="#"
                                                                        class="badge bg-label-danger"
                                                                        id="custom-add-btn"
                                                                        tabindex="0" aria-controls="DataTables_Table_0"
                                                                        type="button" data-bs-toggle="modal"
                                                                        data-bs-target="#discrepency-or-leave-modal"
                                                                        data-toggle="tooltip"
                                                                        data-placement="top"
                                                                        title="Sandwitch Leave"
                                                                        data-url="{{ route('user_leaves.store') }}"
                                                                        data-user="{{ $user->slug }}"
                                                                        data-date='{{$date}}'
                                                                        data-type="absent"
                                                                        data-leave-types="{{ json_encode($leave_types) }}"
                                                                        >
                                                                        Leave
                                                                    </a>
                                                                @else
                                                                    {{'-'}}
                                                                @endif
                                                            @else
                                                                {{'-'}}
                                                            @endif
                                                        @else
                                                            {{ '-' }}
                                                        @endif
                                                    @else
                                                        {{'-'}}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($day!='Sat' && $day!='Sun')
                                                        <span class="punchedin d-block mb-2">{{$reponse['punchOut']}}</span>

                                                        @if($isCurrentTimeInRange)
                                                            @if(empty($applied))
                                                                @if(isset($attendance_adjustment) && !empty($attendance_adjustment->mark_type))
                                                                    @if($attendance_adjustment->mark_type=='lasthalf' || $attendance_adjustment->mark_type=='earlyout')
                                                                        @php $type_label = ''; $apply_date=''; @endphp
                                                                        @if($attendance_adjustment->mark_type=='lasthalf')
                                                                            @php
                                                                                $type_label = 'Apply Leave';
                                                                                // $apply_date = date('d-m-Y', strtotime($attendance_adjustment->hasAttendance->in_date));
                                                                                $apply_date = $i->format("Y-m-d");
                                                                            @endphp
                                                                        @else
                                                                            @php
                                                                                $type_label = 'Apply Discrepency';
                                                                                // $apply_date = $attendance_adjustment->hasAttendance->id;
                                                                                $apply_date = $i->format("Y-m-d");
                                                                            @endphp
                                                                        @endif
                                                                        <a href="#"
                                                                            class="badge bg-label-danger"
                                                                            id="custom-add-btn"
                                                                            tabindex="0" aria-controls="DataTables_Table_0"
                                                                            type="button" data-bs-toggle="modal"
                                                                            data-bs-target="#discrepency-or-leave-modal"
                                                                            data-toggle="tooltip"
                                                                            data-placement="top"
                                                                            title="{{ $type_label }}"
                                                                            data-url="{{ route('user_leaves.store') }}"
                                                                            data-user="{{ $user->slug }}"
                                                                            data-date='{{$apply_date}}'
                                                                            data-type="{{ $reponse['type'] }}"
                                                                            data-leave-types="{{ json_encode($leave_types) }}"
                                                                            >
                                                                            @if($reponse['type']!="earlyout")
                                                                                Leave
                                                                            @else
                                                                                Discrepency
                                                                            @endif
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    @if($reponse['type']=='lasthalf' || $reponse['type']=='earlyout' && checkEmployeeAllowToFillDiscrepancy($user))
                                                                        @php $type_label = ''; $apply_date=''; @endphp
                                                                        @if($reponse['type']=='lasthalf')
                                                                            @php
                                                                                $type_label = 'Apply Leave';
                                                                                $apply_date = $i->format("d-m-Y");
                                                                            @endphp
                                                                        @else
                                                                            @php
                                                                                $type_label = 'Apply Discrepency';
                                                                                // $apply_date = $reponse['attendance_date']->id??'';
                                                                                $apply_date = $i->format("Y-m-d");
                                                                            @endphp
                                                                        @endif
                                                                        @if($reponse['type']=="earlyout")
                                                                            <a href="#"
                                                                                class="badge bg-label-danger"
                                                                                id="custom-add-btn"
                                                                                tabindex="0" aria-controls="DataTables_Table_0"
                                                                                type="button" data-bs-toggle="modal"
                                                                                data-bs-target="#discrepency-or-leave-modal"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="{{ $type_label }}"
                                                                                data-url="{{ route('user_leaves.store') }}"
                                                                                data-user="{{ $user->slug }}"
                                                                                data-date='{{$apply_date}}'
                                                                                data-type="{{ $reponse['type'] }}"
                                                                                data-leave-types="{{ json_encode($leave_types) }}"
                                                                                >
                                                                                Discrepency
                                                                            </a>
                                                                        @elseif($remaining_filable_leaves > 0)
                                                                            <a href="#"
                                                                                class="badge bg-label-danger"
                                                                                id="custom-add-btn"
                                                                                tabindex="0" aria-controls="DataTables_Table_0"
                                                                                type="button" data-bs-toggle="modal"
                                                                                data-bs-target="#discrepency-or-leave-modal"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="{{ $type_label }}"
                                                                                data-url="{{ route('user_leaves.store') }}"
                                                                                data-user="{{ $user->slug }}"
                                                                                data-date='{{$apply_date}}'
                                                                                data-type="{{ $reponse['type'] }}"
                                                                                data-leave-types="{{ json_encode($leave_types) }}"
                                                                                >
                                                                                Leave
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @elseif(!empty($applied) && ($reponse['type']=='lasthalf' || $reponse['type']=='earlyout'))
                                                                @if($applied->status==1)
                                                                    <span class="badge bg-label-success" title="Approved: {{ date('d F Y h:i A', strtotime($applied->updated_at)) }}">Approved</span>
                                                                @elseif($applied->status==2)
                                                                    <span class="badge bg-label-danger" title="Rejected: {{ date('d F Y h:i A', strtotime($applied->updated_at)) }}">Rejected</span>
                                                                @else
                                                                    <span class="badge bg-label-warning" title="Applied At: {{ date('d F Y h:i A', strtotime($applied->created_at)) }}">Pending</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        {{'-'}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($day!='Sat' && $day!='Sun')
                                                        @if(isset($attendance_adjustment) && !empty($attendance_adjustment) && ($i->format("Y-m-d") <= date('Y-m-d')))
                                                            @php $mark_type = $attendance_adjustment->mark_type; @endphp
                                                            @if($mark_type=='firsthalf')
                                                                @php $mark_type = 'Half Day' @endphp
                                                            @endif
                                                            <span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Marked as {{ Str::ucfirst($mark_type) }}</span>
                                                        @else
                                                            @if($day!='Sat' && $day!='Sun')
                                                                {!! $reponse['label'] !!}
                                                            @else
                                                                {{'-'}}
                                                            @endif
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>@if($day!='Sat' && $day!='Sun'){{$reponse['workingHours']}}@else{{'-'}}@endif</td>

                                                @if($user->id != Auth::user()->id)
                                                    <td>
                                                        @if($day!='Sat' && $day!='Sun')
                                                            @php $adjustment_date = $i->format("Y-m-d"); @endphp
                                                            @if((isset($applied) && !empty($applied) && $applied->status==0) && ($i->format("Y-m-d") <= date('Y-m-d')))
                                                                <div class="d-flex align-items-center">
                                                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                        <i class="ti ti-dots-vertical ti-xs mx-1"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                                                        <a href="javascript:;" class="dropdown-item attendance-mark-btn" data-attendance-id="{{ $adjustment_date }}" data-mark-type="absent" data-user="{{ $user->id }}" data-url='{{ route("mark_attendance.store") }}'>
                                                                            Mark As Absent
                                                                        </a>
                                                                        <a href="javascript:;" class="dropdown-item attendance-mark-btn" data-attendance-id="{{ $adjustment_date }}" data-mark-type="fullday" data-user="{{ $user->id }}" data-url='{{ route("mark_attendance.store") }}'>
                                                                            Mark As Full Day
                                                                        </a>
                                                                        <a href="javascript:;" class="dropdown-item attendance-mark-btn" data-attendance-id="{{ $adjustment_date }}" data-mark-type="firsthalf" data-user="{{ $user->id }}" data-url='{{ route("mark_attendance.store") }}'>
                                                                            Mark As Half Day
                                                                        </a>
                                                                        <a href="javascript:;" class="dropdown-item attendance-mark-btn" data-attendance-id="{{ $adjustment_date }}" data-mark-type="lateIn" data-user="{{ $user->id }}" data-url='{{ route("mark_attendance.store") }}'>
                                                                            Mark As Late In
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @elseif(empty($applied) && ($i->format("Y-m-d") <= date('Y-m-d')) && $reponse['label'] != '-')
                                                                <div class="d-flex align-items-center">
                                                                    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                        <i class="ti ti-dots-vertical ti-xs mx-1"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                                                        <a href="javascript:;" class="dropdown-item attendance-mark-btn" data-attendance-id="{{ $adjustment_date }}" data-mark-type="absent" data-user="{{ $user->id }}" data-url='{{ route("mark_attendance.store") }}'>
                                                                            Mark As Absent
                                                                        </a>
                                                                        <a href="javascript:;" class="dropdown-item attendance-mark-btn" data-attendance-id="{{ $adjustment_date }}" data-mark-type="fullday" data-user="{{ $user->id }}" data-url='{{ route("mark_attendance.store") }}'>
                                                                            Mark As Full Day
                                                                        </a>
                                                                        <a href="javascript:;" class="dropdown-item attendance-mark-btn" data-attendance-id="{{ $adjustment_date }}" data-mark-type="firsthalf" data-user="{{ $user->id }}" data-url='{{ route("mark_attendance.store") }}'>
                                                                            Mark As Half Day
                                                                        </a>
                                                                        <a href="javascript:;" class="dropdown-item attendance-mark-btn" data-attendance-id="{{ $adjustment_date }}" data-mark-type="lateIn" data-user="{{ $user->id }}" data-url='{{ route("mark_attendance.store") }}'>
                                                                            Mark As Late In
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @else
                                                            -
                                                            @endif
                                                        @else
                                                            {{'-'}}
                                                        @endif
                                                    </td>
                                                @endif
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
   
    <!-- Apply Leave Or Discrepency Modal -->
@endsection
@push('js')
<script src="{{ asset('public/admin/assets/js/custom-dashboard.js') }}"></script>
<script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    $(document).on('click', '#custom-add-btn', function(){
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
        if(type=='lateIn' || type=='earlyout'){
            $('#leave_types_div').hide();
        }else if(type=='firsthalf' || type=='lasthalf'){
            $('#leave_types_div').show();
            $.each(leave_types, function(index, val) {
                if(val.name=='Half-Day'){
                    html += '<option value="'+val.id+'" selected>'+val.name+'</option>';
                }
            });
        }else{
            $('#leave_types_div').show();
            $.each(leave_types, function(index, val) {
                if(val.name!='Half-Day'){
                    html += '<option value="'+val.id+'">'+val.name+'</option>';
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
            startDate:joiningMonthYear,
            endDate: currentMonth,
        }).on('changeMonth', function(e) {
            var employeeSlug = $('#employee-slug option:selected').data('user-slug');
            if(employeeSlug==undefined){
                employeeSlug = $('#current_user_slug').val();
            }
            var selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
            var selectedYear = e.date.getFullYear();

            var selectOptionUrl = "{{ URL::to('employee/attendance/summary') }}/" + selectedMonth + "/" + selectedYear + "/" + employeeSlug;

            window.location.href = selectOptionUrl;
        });

        const url = new URL(window.location.href);
        const pathname = url.pathname;
        const pathParts = pathname.split('/');
        if(pathParts.length > 6){
            const emp = pathParts.pop();
            const year = pathParts.pop();
            const month = pathParts.pop();

            $('#Slipbutton').datepicker('setDate', new Date(year, month-1));
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
            $(document).on('changeMonth', '.datepicker', function (e) {
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
