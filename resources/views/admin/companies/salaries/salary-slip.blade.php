<!DOCTYPE html>
<html lang="en">

<head>
    <title>Salary Slip of {{ date('F Y', strtotime($year . '-' . $month)) }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://portal.cyberonix.co/public/admin/assets/vendor/fonts/tabler-icons.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(!empty(settings()))
    <link rel="icon" type="image/x-icon" href="{{ asset('public/admin/favicon.png') }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('public/admin/favicon.png') }}" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        a:hover {
            text-decoration: none;
        }

        .stamp-logo {
            width: 10%;
        }

        .form-fields a {
            background: linear-gradient(72.47deg, rgba(234, 84, 85) 22.16%, rgba(234, 84, 85, 0.7) 76.47%);
            /*box-shadow: 0px 2px 6px 0px rgba(234,84,85, 0.48);*/
            color: #fff;
            padding: 6px 11px;
            border-radius: 2px;
            font-weight: 400;
        }

        .form-fields button,
        .form-fields button:focus {
            background: linear-gradient(72.47deg, rgba(234, 84, 85) 22.16%, rgba(234, 84, 85, 0.7) 76.47%);
            /*box-shadow: 0px 2px 6px 0px rgba(234,84,85, 0.48);*/
            padding: 5px 25px;
            border-radius: 2px;
            border: none;
            outline: none;
            color: #fff;
        }

        .Cyberonix-Logo {
            width: 25%;
        }

        .btn-primary {
            color: #fff !important;
            background-color: #e30b5c !important;
            border-color: #e30b5c !important;
            border-radius: 0.375rem !important;
            outline: none !important;
        }

        .btn-label-primary {
            color: #e30b5c !important;
            border-color: transparent !important;
            background: #fbdae7 !important;
            border-radius: 0.375rem !important;
            outline: none !important;
        }


        @media print {

            #pdfPrint,
            .form-fields {
                display: none !important;
            }
        }
    </style>
</head>

<body class="py-5">
    @if (isset($user) && !empty($user))
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-12" id="hide">
                        <div class="form-fields d-inline-block pull-right mt-3 float-right">
                            {{-- <a href="{{ route('employees.salary_details') }}" class=text-capitalize mr-3 btn-label-primary"> <i class="fa fa-back me-1"></i> Go Back </a> --}}
                            <a href="{{ URL::to('employees/salary_details/' . $month . '/' . $year . '/' . $user->slug) }}"
                                class="text-capitalize mr-3 btn-label-primary"> <i class="fa fa-back me-1"></i> Go Back
                            </a>
                            <button title="Download" id="pdfPrint" class="text-capitalize btn-primary" type="button"
                                name="pdfPrint">
                                <span><i class="ti ti-printer me-1"></i></span>Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div id="contentToConvert">
            <section class="mt-3 salary-table">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="text-center">
                            @if (isset($setting) && !empty($setting->black_logo))
                                <img src="{{ $setting->base_url }}/public/admin/assets/img/logo/{{ $setting->black_logo }}"
                                    style="max-width: 250px;width:100%" class="light-logo img-logo"
                                    alt="{{ $setting->name }}" />
                            @else
                                <img src="{{ asset('public/admin/default.png') }}" class="img-fluid light-logo img-logo"
                                    title="Company Black Logo Here..." alt="Default" />
                            @endif
                            <h6 class="mt-3 font-weight-bold h5">Salary Slip for the month of
                                {{ date('F Y', strtotime($year . '-' . $month)) }}</h6>
                        </div>
                    </div>
                </div>
                <div class="container">
                    @if(Auth::user()->hasPermissionTo('salaries-generate-salary-slip'))
                    <div class="row mb-3">
                        <div class="col-md-6 pr-0 ">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row" contenteditable>Employee No. </th>
                                        <td contenteditable>
                                            @if (isset($user->profile) && !empty($user->profile->employment_id))
                                                {{ $user->profile->employment_id }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>CNIC #</th>
                                        <td contenteditable>
                                            @if (!empty($user->profile->cnic))
                                                {{ $user->profile->cnic }}
                                            @else
                                                @if (isset($user->hasPreEmployee) && !empty($user->hasPreEmployee->cnic))
                                                    {{ $user->hasPreEmployee->cnic }}
                                                @else
                                                    N/A
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Designation </th>
                                        <td contenteditable>
                                            @if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
                                                {{ $user->jobHistory->designation->title }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Total Days </th>
                                        <td contenteditable>{{ $totalDays }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Per Day Salary </th>
                                        <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                            {{ number_format($per_day_salary) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Bank </th>
                                        <td contenteditable>
                                            {{ !empty($user->bankDetails) ? $user->bankDetails->bank_name : '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 pl-0 ">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row" contenteditable>Employee Name</th>
                                        <td contenteditable>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Appointment Date</th>
                                        <td contenteditable>
                                            @if (isset($user->profile) && !empty($user->profile->joining_date))
                                                {{ date('d M Y', strtotime($user->profile->joining_date)) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Department</th>
                                        <td contenteditable>
                                            @if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->name))
                                                {{ $user->departmentBridge->department->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Earning Days</th>
                                        <td contenteditable>{{ $total_earning_days }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Pay Through</th>
                                        <td contenteditable>Interbank Funds Transfer</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" contenteditable>Branch Code</th>
                                        <td contenteditable>
                                            {{ !empty($user->bankDetails) ? $user->bankDetails->branch_code : '' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 pr-0 ">
                            <div class="custom-table">
                                <h5 class="border text-center py-3 border-top-0 mb-0 border-bottom-0" contenteditable>
                                    Salary & Allowances</h5>
                                <table class="table table-bordered mb-0 col-md-12">
                                    <thead>
                                        <tr>
                                            <th scope="row" contenteditable>Title </th>
                                            <th scope="row" contenteditable>Actual </th>
                                            <th scope="row" contenteditable>Earning </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row" contenteditable>Basic </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($salary) }} </td>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($earning_days_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>House Rent</th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Medical </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Cost Of Living Allowance</th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Fuel Allowance </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Car Allowance </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($car_allowance) }}</td>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($car_allowance) }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Arrears </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Extra Days Amount </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="text-center" contenteditable>Total</th>
                                            <th scope="row" contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ $total_actual_salary }}</th>
                                            <th scope="row" contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ $total_earning_salary }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 pl-0 ">
                            <div class="custom-table">
                                <h5 class="border text-center py-3 border-top-0 mb-0 border-bottom-0" contenteditable>
                                    Deductions</h5>
                                <table class="table table-bordered mb-0 col-md-12">
                                    <thead>
                                        <tr>
                                            <th scope="row" contenteditable>Title </th>
                                            <th scope="row" contenteditable>Amount </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row" contenteditable>Absent Days Amount </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($absent_days_amount) }}</td>
                                        </tr>
                                        @php $boo = true @endphp
                                        @if ($extra_availed_leave_days_amount > 0)
                                            @php $boo = false @endphp
                                            <tr>
                                                <th scope="row" contenteditable>Advance Availed Leave Days Amount
                                                </th>
                                                <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                    {{ number_format($extra_availed_leave_days_amount) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th scope="row" contenteditable>Half Days Amount</th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($half_days_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Late In + Early Out Amount </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($late_in_early_out_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Income Tax</th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>EOBI </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Loan Installment </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" contenteditable>Advance Salary </th>
                                            <td contenteditable>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        @if ($boo)
                                            <tr class="invisible">
                                                <th scope="row">Advance Salary </th>
                                                <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th scope="row" class="text-center" contenteditable>NET SALARY</th>
                                            <th scope="row" contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                {{ $net_salary }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-6 pr-0">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Employee No. </th>
                                        <td>
                                            @if (isset($user->profile) && !empty($user->profile->employment_id))
                                                {{ $user->profile->employment_id }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">CNIC #</th>
                                        <td>
                                            @if (!empty($user->profile->cnic))
                                                {{ $user->profile->cnic }}
                                            @else
                                                @if (isset($user->hasPreEmployee) && !empty($user->hasPreEmployee->cnic))
                                                    {{ $user->hasPreEmployee->cnic }}
                                                @else
                                                    N/A
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Designation </th>
                                        <td>
                                            @if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
                                                {{ $user->jobHistory->designation->title }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Total Days </th>
                                        <td>{{ $totalDays }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Per Day Salary </th>
                                        <td>{{ $currency_code ?? 'Rs.' }} {{ number_format($per_day_salary) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Bank </th>
                                        <td>{{ !empty($user->bankDetails) ? $user->bankDetails->bank_name : '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 pl-0">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Employee Name</th>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Appointment Date</th>
                                        <td>
                                            @if (isset($user->profile) && !empty($user->profile->joining_date))
                                                {{ date('d M Y', strtotime($user->profile->joining_date)) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Department</th>
                                        <td>
                                            @if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->name))
                                                {{ $user->departmentBridge->department->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Earning Days</th>
                                        <td>{{ $total_earning_days }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Pay Through</th>
                                        <td>Interbank Funds Transfer</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Branch </th>
                                        <td>{{ !empty($user->bankDetails) ? $user->bankDetails->branch_code : '' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 pr-0">
                            <div class="custom-table">
                                <h5 class="border text-center py-3 border-top-0 mb-0 border-bottom-0">Salary &
                                    Allowances</h5>
                                <table class="table table-bordered mb-0 col-md-12">
                                    <thead>
                                        <tr>
                                            <th scope="row">Title </th>
                                            <th scope="row">Actual </th>
                                            <th scope="row">Earning </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Basic </th>
                                            <td>{{ number_format($salary) }} </td>
                                            <td>{{ number_format($earning_days_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">House Rent</th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Medical </th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Cost Of Living Allowance</th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Fuel Allowance </th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Car Allowance </th>
                                            <td>{{ $currency_code ?? 'Rs.' }} {{ number_format($car_allowance) }}</td>
                                            <td>{{ $currency_code ?? 'Rs.' }} {{ number_format($car_allowance) }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Arrears </th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Extra Days Amount </th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0 </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="text-center">Total</th>
                                            <th scope="row">{{ $currency_code ?? 'Rs.' }}
                                                {{ $total_actual_salary }}</th>
                                            <th scope="row">{{ $currency_code ?? 'Rs.' }}
                                                {{ $total_earning_salary }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 pl-0">
                            <div class="custom-table">
                                <h5 class="border text-center py-3 border-top-0 mb-0 border-bottom-0">Deductions</h5>
                                <table class="table table-bordered mb-0 col-md-12">
                                    <thead>
                                        <tr>
                                            <th scope="row">Title </th>
                                            <th scope="row">Amount </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Absent Days Amount </th>
                                            <td>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($absent_days_amount) }}</td>
                                        </tr>
                                        @php $boo = true @endphp
                                        @if ($extra_availed_leave_days_amount > 0)
                                            @php $boo = false @endphp
                                            <tr>
                                                <th scope="row" contenteditable>Advance Availed Leave Days Amount
                                                </th>
                                                <td contenteditable>{{ $currency_code ?? 'Rs.' }}
                                                    {{ number_format($extra_availed_leave_days_amount) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th scope="row">Half Days Amount</th>
                                            <td>{{ $currency_code ?? 'Rs.' }} {{ number_format($half_days_amount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Late In + Early Out Amount </th>
                                            <td>{{ $currency_code ?? 'Rs.' }}
                                                {{ number_format($late_in_early_out_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Income Tax</th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">EOBI </th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Loan Installment </th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Advance Salary </th>
                                            <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                        </tr>
                                        @if ($boo)
                                            <tr class="invisible">
                                                <th scope="row">Advance Salary </th>
                                                <td>{{ $currency_code ?? 'Rs.' }} 0</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th scope="row" class="text-center">NET SALARY</th>
                                            <th scope="row">{{ $currency_code ?? 'Rs.' }} {{ $net_salary }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <footer>
                <div class="container">
                    <div class="row ">
                        <div class="col-12">
                            <p class="mt-5" contenteditable><span class="font-weight-bold">Note: </span>This is a
                                computer generated salary slip and does not require signatures.</p>

                            @if (isset($setting) && !empty($setting->slip_stamp))
                                <img src="{{ asset('public/admin/assets/img/logo') }}/{{ $setting->slip_stamp }}"
                                    class="img-fluid light-logo stamp-logo" alt="{{ $setting->name }}" />
                            @else
                                <img src="{{ asset('public/admin/default.png') }}"
                                    class="img-fluid light-logo stamp-logo" title="Company Slip Stamp Here..."
                                    alt="Default" />
                            @endif
                        </div>
                    </div>
                </div>

            </footer>
        </div>
    @endif


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('public/admin/assets/js/html2pdf.js') }}"></script>
    <script>
        $(document).ready(function() {
            // console.log(html2pdf());
            $("#pdfPrint").click(function() {

                // Get the HTML content to be converted
                var htmlContent = document.getElementById("contentToConvert");
              
                // Use html2pdf library to convert HTML to PDF
                html2pdf(htmlContent, {
                    margin: 10,
                    filename: '{{ $user->first_name }} {{ date('F Y', strtotime($year . '-' . $month)) }} Salary Slip.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a3',
                        orientation: 'portrait'
                    },
                }).from(htmlContent);
            });


            $(document).keydown(function(event) {
                // Check if Ctrl key (or Command key on Mac) is pressed and the 'P' key is pressed
                if (event.ctrlKey || event.metaKey) {
                    if (String.fromCharCode(event.which).toLowerCase() === 'p') {
                        // Prevent the default action (printing)
                        event.preventDefault();
                    }
                }
            });

        });
    </script>
</body>

</html>
