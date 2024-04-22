<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body pb-0">
            <div class="card-icon">
                <span class="badge bg-label-success rounded-pill p-2">
                <i class="ti ti-credit-card ti-sm"></i>
                </span>
            </div>
            {{-- <h5 class="card-title mb-0 mt-2">
                @php $monthlyRegulars = []; @endphp
                @if (isset(getDailyAttendanceReport()['today_regulars']))
                    @php $monthlyRegulars = getDailyAttendanceReport()['monthly_regulars'] @endphp
                    {{ getDailyAttendanceReport()['today_regulars'] }}
                @else
                    0
                @endif
                </h5> --}}
                <h5 class="card-title mb-0 mt-2" id="regular-employees-count">Loading...</h5>
                <small>Regular Employees</small>
            </div>
            {{-- <div id="regular-employees" data-regulars="{{ json_encode($monthlyRegulars) }}"></div> --}}
            <div id="regular-employees" data-regular-employees=""></div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body pb-0">
            <div class="card-icon">
                <span class="badge bg-label-warning rounded-pill p-2">
                <i class="ti ti-credit-card ti-sm"></i>
                </span>
            </div>
            {{-- <h5 class="card-title mb-0 mt-2">
                @php $monthlyLateIns = []; @endphp
                @if (isset(getDailyAttendanceReport()['today_lateIns']))
                    @php $monthlyLateIns = getDailyAttendanceReport()['monthly_lateIns'] @endphp
                    {{ getDailyAttendanceReport()['today_lateIns'] }}
                @else
                    0
                @endif
            </h5> --}}
            <h5 class="card-title mb-0 mt-2" id="late-in-employees-count">Loading...</h5>
            <small>Late In Employees</small>
            </div>
            {{-- <div id="late-in-employees" data-late-in="{{ json_encode($monthlyLateIns) }}"></div> --}}
            <div id="late-in-employees" data-late-in-employees=""></div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body pb-0">
            <div class="card-icon">
                <span class="badge bg-label-primary rounded-pill p-2">
                <i class="ti ti-credit-card ti-sm"></i>
                </span>
            </div>
            {{-- <h5 class="card-title mb-0 mt-2">
                @php $monthlyHalfDays = []; @endphp
                @if (isset(getDailyAttendanceReport()['today_hafDays']))
                    @php $monthlyHalfDays = getDailyAttendanceReport()['monthly_hafDays'] @endphp
                    {{ getDailyAttendanceReport()['today_hafDays'] }}
                @else
                    0
                @endif
            </h5> --}}
            <h5 class="card-title mb-0 mt-2" id="half-day-employees-count">Loading...</h5>
            <small>Half Day Leave Employees</small>
            </div>
            {{-- <div id="half-day-employees" data-half-day="{{ json_encode($monthlyHalfDays) }}"></div> --}}
            <div id="half-day-employees" data-half-day-employees=""></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body pb-0">
            <div class="card-icon">
                <span class="badge bg-label-danger rounded-pill p-2">
                <i class="ti ti-credit-card ti-sm"></i>
                </span>
            </div>
            {{-- <h5 class="card-title mb-0 mt-2">
                @php $monthlyAbsents = []; @endphp
                @if (isset(getDailyAttendanceReport()['today_absents']))
                    @php $monthlyAbsents = getDailyAttendanceReport()['monthly_absents'] @endphp
                    {{ getDailyAttendanceReport()['today_absents'] }}
                @else
                    0
                @endif
            </h5> --}}
            <h5 class="card-title mb-0 mt-2" id="absent-employees-count">Loading...</h5>
            <small>Absent Employees</small>
            </div>
            {{-- <div id="absent-employees" data-absents="{{ json_encode($monthlyAbsents) }}"></div> --}}
            <div id="absent-employees" data-absent-employees=""></div>
        </div>
    </div>
</div>
