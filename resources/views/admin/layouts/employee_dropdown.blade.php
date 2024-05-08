@if(isset($employees['total_employees']) && !empty($employees['total_employees']))
    @if($type=='redirect')
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <select class="select2 form-select" id="redirectDropdown" onchange="redirectPage(this)">
                    <option value="{{ $url }}" selected>Select employee</option>
                    @foreach ($employees['total_employees'] as $employee)
                        @if(isset($month) && isset($year))
                            <option value="{{route('admin.companies.attendance',['company'=>$company,'month'=>$month,'year'=>$year,'slug'=>$employee->slug])}}" >
                                {{ $employee->name }}  ({{$employee->employment_id }})
                            </option>
                        @else
                            <option data-user-slug="{{ $employee->slug }}" value="{{route('admin.companies.attendance',['company'=>$company,'slug'=>$employee->slug])}}" >
                                {{ $employee->name }} ({{$employee->employment_id }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    @elseif($type=='salary-details')
        <select class="select2 form-select form-select-lg" data-allow-clear="true" id="employee-slug" onchange="redirectPage(this)">
            <option value="{{ $url }}" selected>Select employee</option>
            @foreach ($employees['total_employees'] as $employee)
                @if(!empty($employee))
                    @php $monthYear = $month.'/'.$year; @endphp
                    @if (!empty($employee->employeeStatus->end_date) && ($monthYear < date('m/Y', strtotime($employee->employeeStatus->end_date))))
                        @php
                            $lastMonth = date('m', strtotime($employee->employeeStatus->end_date));
                            $lastYear = date('Y', strtotime($employee->employeeStatus->end_date));
                        @endphp
                        <option value="{{route('admin.companies.attendance',['company'=>$company,'month'=>$month,'year'=>$year,'slug'=>$employee->slug])}}" data-user-slug="{{ $employee->slug }}">
                            {{ $employee->name }} ({{$employee->employment_id }})
                        </option>
                    @else
                    <option value="{{route('admin.companies.attendance',['company'=>$company,'month'=>$month,'year'=>$year,'slug'=>$employee->slug])}}" data-user-slug="{{ $employee->slug }}" >
                            {{ $employee->name }} ({{$employee->employment_id }})
                        </option>
                    @endif
                @endif
            @endforeach
        </select>
    @elseif($type=='filter')
        <select class="select2 form-select" id="employees_ids" name="employees[]" multiple>
            <option value="All" selected>All Employees</option>
            @foreach ($employees['total_employees'] as $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }} ({{$employee->employment_id }})</option>
            @endforeach
        </select>
    @elseif($type=='terminated-summary')
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <select class="select2 form-select" id="redirectDropdown" onchange="redirectPage(this)">
                    <option value="{{ $url }}" selected>Select terminated employee</option>
                    @foreach ($employees['total_employees'] as $employee)
                        @php
                            $lastMonth = date('m', strtotime($employee->employeeStatus->end_date));
                            $lastYear = date('Y', strtotime($employee->employeeStatus->end_date));
                        @endphp
                        <option value="{{route('admin.companies.attendance.summary',['company'=>$company,'month'=>$month,'year'=>$year,'slug'=>$employee->slug])}}" data-user-slug="{{ $employee->slug }}" >
                            {{ $employee->name }} ({{$employee->employment_id }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
@endif