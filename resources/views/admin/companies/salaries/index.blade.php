@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">

                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="row p-3">
                    <div class="col-md-3 mb-3">
                        <label for="">Company</label>
                        <select name="company" id="company" data-control="select2"
                            class="select2 form-select company unselectValue">
                            <option value="">Select</option>
                            @if (isset($companies) && !empty($companies))
                                @foreach ($companies as $index => $item)
                                    <option value="{{ $item->company_key }}"
                                        @if ($item->company_key == $company) selected @endif>
                                        {{ $item->name }}</option>
                                @endforeach


                            @endif

                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="">Employee</label>
                        <select name="employee" id="employee" data-control="select2"
                            class="select2 form-select employee  unselectValue">
                            <option value="">Select</option>
                            @if (isset($employees['total_employees']) && !empty($employees))
                                @foreach ($employees['total_employees'] as $item)
                                    <option value="{{ $item->slug }}" @if (!empty($user) && $user->slug == $item->slug) selected @endif>
                                        {{ $item->name }} ({{ $item->employment_id }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="">Month</label>
                        <input type="text" id="month-list" data-joining-date="{{ $user_joining_date }}"
                            data-company="{{ $company }}" data-current-month="{{ $currentMonth }}"
                            placeholder="Select Month" class="form-control flatpickr-input " readonly>
                    </div>

                    <div class="col-md-2 mt-3 py-1">
                        <button type="button" id="filter" class="btn btn-primary searchBtn me-2"><i
                                class="fa-solid fa-filter"></i></button>

                    </div>
                </div>
            </div>
            @if (isset($user) && !empty($user))
                <div class="card">
                    <div class="card-header border-bottom">
                        <div id="printable_div">
                            <div class="col-12 mt-3">
                                <span class="card-title mb-0">

                                    <div
                                        class="user-profile-header mt-4 d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                                        <div class="flex-shrink-0 mt-2 mx-sm-0 mx-auto">
                                            @if (isset($user->profile) && !empty($user->profile->profile))
                                                <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $user->profile->profile }}"
                                                    width="70" height="70" alt="user image"
                                                    class="d-block rounded-circle object-fit-cover" />
                                            @else
                                                <img src="{{ asset('public/admin') }}/default.png" alt="Default image"
                                                    width="70" height="70"
                                                    class="d-block rounded-circle object-fit-cover" />
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div
                                                class="d-flex align-items-md-center align-items-sm-center align-items-center justify-content-md-between justify-content-start ms-4 flex-md-row flex-column gap-4">
                                                <div class="user-profile-info">
                                                    <h4 class="mb-1 text-capitalize">{{ $user->first_name }}
                                                        {{ $user->last_name }}</h4>

                                                    <ul class="list-unstyled user-profile-info">
                                                        <li class="mb-1">
                                                            <span class="fw-semibold me-1">Email:</span>
                                                            <span>
                                                                {{ $user->email }}
                                                            </span>
                                                        </li>
                                                        <li class="mb-1">
                                                            <span class="fw-semibold me-1">Employment ID:</span>
                                                            <span>
                                                                @if (isset($user->profile) && !empty($user->profile))
                                                                    {{ $user->profile->employment_id }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </span>
                                                        </li>
                                                        <li class="mb-1">
                                                            <span class="fw-semibold me-1">Designation:</span>
                                                            <span>
                                                                @if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
                                                                    {{ $user->jobHistory->designation->title }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>


                                              @can('salaries-create-salary-slip') 
                                                @if ($user->employeeStatus->end_date != null)
                                                    @php $monthYear = $month.'/'.$year; @endphp
                                                    @if ($monthYear > date('m/Y', strtotime($user->employeeStatus->end_date)))
                                                        @php
                                                            $month = date(
                                                                'm',
                                                                strtotime($user->employeeStatus->end_date),
                                                            );
                                                            $year = date(
                                                                'Y',
                                                                strtotime($user->employeeStatus->end_date),
                                                            );
                                                        @endphp
                                                    @endif
                                                @endif
                                     
                                                    <a href="{{ route('salaries.generate.salary.slip', ['company' => $company, 'month' => $month, 'year' => $year, 'slug' => $slug]) }}"
                                                        target="_blank" class="btn btn-primary waves-effect waves-light"><i
                                                            class="ti ti-printer me-1"></i>Generate Salary Slip </a>
                                                
                                               @endcan 


                                            </div>
                                        </div>
                                    </div>

                                </span>
                            </div>

                            @if (isset($message))
                                <div class="card-datatable table-responsive text-center">
                                    <h4>{{ $message }}</h4>
                                </div>
                            @else
                                <div class="card-datatable table-responsive">
                                    <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                                        <div class="row me-2">
                                            <div class="col-md-2">
                                                <div class="me-3">
                                                    <div class="dataTables_length" id="DataTables_Table_0_length"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <h4 class="text-center">Payslip - {{ $month_year }}</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <table class="table-striped table salary-table">
                                                    <tbody>
                                                        <tr>
                                                            <th>
                                                                <h6 class="mb-0">Employee No.</h6>
                                                            </th>
                                                            <td class="text-end">
                                                                @if (isset($user->profile) && !empty($user->profile->employment_id))
                                                                    {{ $user->profile->employment_id }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <h6 class="mb-0">Designation</h6>
                                                            </th>
                                                            <td class="text-end">
                                                                @if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
                                                                    {{ $user->jobHistory->designation->title }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <h6 class="mb-0">Total Days</h6>
                                                            </th>
                                                            <td class="text-end"> {{ $totalDays }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <h6 class="mb-0">Per Day Salary</h6>
                                                            </th>
                                                            <td class="text-end">{{ $currency_code ?? 'Rs.' }}
                                                                {{ number_format($per_day_salary) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-sm-6">
                                                <table class="table-striped table salary-table">
                                                    <tbody>
                                                        <tr>
                                                            <th>
                                                                <h6 class="mb-0">Employee Name</h6>
                                                            </th>
                                                            <td class="text-end text-capitalize">
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <h6 class="mb-0">Appointment Date</h6>
                                                            </th>
                                                            <td class="text-end">
                                                                @if (isset($user->profile) && !empty($user->profile->joining_date))
                                                                    {{ date('d M Y', strtotime($user->profile->joining_date)) }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <h6 class="mb-0">Department</h6>
                                                            </th>
                                                            <td class="text-end">
                                                                @if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->name))
                                                                    {{ $user->departmentBridge->department->name }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <h6 class="mb-0">Earning Days</h6>
                                                            </th>
                                                            <td class="text-end">{{ $total_earning_days }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <table class="table-striped table mt-3 salary-table">
                                                    <thead>
                                                        <tr class="py-2">
                                                            <th>Title</th>
                                                            <th class="text-center">Actual</th>
                                                            <th class="text-center">Earning</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Basic Salary</h5>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ number_format($salary) }}</p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ number_format($earning_days_amount) }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">House Rent
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0</p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Medical
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Cost Of Living Allowance
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Fuel Allowance
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Car Allowance
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ number_format($car_allowance) }}</p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ number_format($car_allowance) }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Arrears
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Extra Days Amount
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Total Earnings
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ $total_actual_salary }}</p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ $total_earning_salary }}</p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-sm-6">
                                                <table class="table-striped table mt-3 salary-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Title</th>
                                                            <th class="text-center">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Absent Days Amount
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ isset($absent_days_amount) ? number_format($absent_days_amount) : 0 }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        @php $boo = true @endphp
                                                        @if ($extra_availed_leave_days_amount > 0)
                                                            @php $boo = false @endphp
                                                            <tr>
                                                                <td>
                                                                    <h6 class="mb-0">Advance Availed Leave Days Amount
                                                                </td>
                                                                <td>
                                                                    <p class="mb-0 text-center">
                                                                        {{ $currency_code ?? 'Rs.' }}
                                                                        {{ number_format($extra_availed_leave_days_amount) }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Half Days Amount
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ isset($half_days_amount) ? number_format($half_days_amount) : 0 }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Late In + Early Out Amount
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ isset($late_in_early_out_amount) ? number_format($late_in_early_out_amount) : 0 }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Income Tax (will be calculated at the
                                                                    time
                                                                    of salary)
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">EOBI
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Loan Installment
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">Advance Salary
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    0
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        @if ($boo)
                                                            <tr>
                                                                <td colspan="2">&nbsp;</td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0">NET SALARY
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 text-center">{{ $currency_code ?? 'Rs.' }}
                                                                    {{ isset($net_salary) ? $net_salary : 0 }}</p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).on('change', ".company", function() {

            var company = $(this).val();
            $.ajax({
                type: "get",
                url: "{{ route('admin.get.company.employees') }}",
                data: {
                    company: company
                },

                success: function(res) {
                    if (res.success == true) {
                        var employee_list = $("#employee");
                        var employees = res.data;
                        employee_list.empty();
                        employee_list.append('<option value="">Select</>')
                        employees.forEach(function(employee) {

                            employee_list.append('<option value="' + employee.slug +
                                '" >' +
                                employee.name + '(' + employee.employment_id +
                                ') </option>');
                        });

                    } else {
                        var employee_list = $("#employee");
                        employee_list.empty();
                        employee_list.append('<option value="">No Employee Found</>')
                    }
                }
            });


        })
        var currentMonth = $('#month-list').data('current-month');
        var joiningMonthYear = $('#month-list').data('joining-date');
        var selectedMonth, selectedYear, employeeSlug;
        var urlParams = new URLSearchParams(window.location.search);
        var month = urlParams.get('month');
        var year = urlParams.get('year');

        var initialDate = '';
        if (month && year) {
            initialDate = new Date(year, month - 1);
        }
        $('#month-list').datepicker({
            format: 'mm/yyyy',
            startView: 'year',
            minViewMode: 'months',
            endDate: currentMonth,

        }).on('changeMonth', function(e) {

            //  employeeSlug = $('#employee-slug option:selected').data('user-slug');

            console.log(e.date.getMonth())
            selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
            selectedYear = e.date.getFullYear();



        });

        $(document).on("click", "#filter", function() {
            var company = $("#company").val();
            employeeSlug = $("#employee").val();
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

            var selectOptionUrl = "{{ URL::to('salaries/details') }}/?company=" + company + "&month=" +
                selectedMonth + "&year=" + selectedYear + "&slug=" + employeeSlug;

            window.location.href = selectOptionUrl;
        })
    </script>
@endpush
