<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\{
    DB,
    Auth
};
use App\Http\Controllers\Admin\{
    AttendanceController
};
use App\Models\{
    User,
    Company,
    Holiday,
    Setting,
    UserLeave,
    WorkShift,
    Attendance,
    Department,
    Discrepancy,
    PreEmployee,
    UserContact,
    VehicleUser,
    SalaryHistory,
    DepartmentUser,
    WorkingShiftUser,
    AttendanceSummary,
    AttendanceAdjustment,
    Grievance,
    HolidayCustomizeEmployee,
    MonthlySalaryReport,
    EmployeeLetter
};

function settings()
{

    return Setting::first();
}

function defaultShift()
{
    return WorkShift::where('is_default', 1)->where('status', 1)->first();
}

function appName()
{
    return env("APP_NAME");
    foreach (companies() as $index => $portalDb) {
        if (!empty($company) && $company == $index) {
            dd($company, $index);
            $setting = Setting::on($portalDb)->first();
        }
    }
    if (isset($setting) && !empty($setting->name)) {
        $app_name = $setting->name;
    } else {
        $app_name = '-';
    }

    return $app_name;
}
function SubPermissions($label)
{
    return Permission::where('label', $label)->get();
}

function loginUser()
{
    if (Auth::check()) {
        $profile = null;
        if (isset(Auth::user()->profile) && !empty(Auth::user()->profile->profile)) {
            $profile = Auth::user()->profile->profile;
        }
        $user = (object)[
            'id' => Auth::user()->id,
            'slug' => Auth::user()->slug,
            'name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'email' => Auth::user()->email,
            'profile' => $profile,
            'role' => Auth::user()->getRoleNames()->first(),
        ];

        return $user;
    } else {
        return null;
    }
}

function getUserData($user)
{
    $profile = null;
    if (isset($user->profile) && !empty($user->profile->profile)) {
        $profile = $user->profile->profile;
    }
    $designation = '-';
    if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title)) {
        $designation = $user->jobHistory->designation->title;
    }
    $user = (object)[
        'id' => $user->id,
        'slug' => $user->slug,
        'name' => $user->first_name . ' ' . $user->last_name,
        'email' => $user->email,
        'profile' => $profile,
        'role' => $user->getRoleNames()->first(),
        'designation' => $designation,
    ];
    return $user;
}

function companies()
{
    $companies = [
        'cyberonix_hr' => env('CYBERONIX_DB_DATABASE'),
        // 'vertical' => env('VERTICAL_DB_DATABASE'),
        // 'braincell' => env('BRAINCELL_DB_DATABASE'),
        // 'clevel' => env('CLEVEL_DB_DATABASE'),
        // 'delve' => env('DELVE12_DB_DATABASE'),
        // 'horizontal' => env('HORIZONTAL_DB_DATABASE'),
        // 'mercury' => env('MERCURY_DB_DATABASE'),
        // 'momyom' => env('MOMYOM_DB_DATABASE'),
        // 'softnova' => env('SOFTNOVA_DB_DATABASE'),
        // 'softfellow' => env('SOFTFELLOW_DB_DATABASE'),
        // 'swyftcube' => env('SWYFTCUBE_DB_DATABASE'),
        // 'swyftzone' => env('SWYFTZONE_DB_DATABASE'), // currently not in used
        // 'techcomrade' => env('TECHCOMRADE_DB_DATABASE'),
        // 'rocketflare' => env('ROCKETFLARELABS_DB_DATABASE'),
    ];

    return $companies;
}
function getAllCompanies()
{
    $companies = [];
    // Get the current month and year
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;
    foreach (companies() as $portalName => $portalDb) {
        $settings = Setting::on($portalDb)->select(['id', 'base_url', 'name', 'phone_number', 'email', 'favicon'])->first();

        if (!empty($settings)) {
            $total_employees = User::on($portalDb)->where('is_employee', 1)->with('profile:user_id,profile,employment_id')->select(['id', 'slug', 'first_name', 'last_name', 'email'])->get();
            $total_new_hiring =  User::on($portalDb)
                ->where('is_employee', 1)
                ->whereHas('profile', function ($query) use ($currentMonth, $currentYear) {
                    $query->whereMonth('joining_date', $currentMonth)
                        ->whereYear('joining_date', $currentYear);
                })
                ->with('profile:user_id,profile,employment_id')
                ->select(['id', 'slug', 'first_name', 'last_name', 'email'])
                ->get();

            $total_terminated_employees = User::on($portalDb)->wherehas('employeeStatusEndDateNull', function ($q) {
                $q->where('employment_status_id', 3);
            })->where('is_employee', 0)->get();

            $terminatedUsersOfCurrentMonth = User::on($portalDb)->wherehas('employeeStatusEndDateNull', function ($q) {
                $q->where('employment_status_id', 3);
            })->where('is_employee', 0)->whereHas('hasResignation', function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('last_working_date', $currentMonth)
                    ->whereYear('last_working_date', $currentYear);
            })->get();
            $vehicleUsers =  VehicleUser::on($portalDb)
                ->with([
                    'hasVehicle' => function ($query) {
                        $query->select('id', 'owner_id', 'name', 'thumbnail', 'model_year', 'engine_capacity');
                        $query->with('hasImage');
                    },
                    'hasUser' => function ($query) {
                        $query->select('id', 'slug', 'status', 'first_name', 'last_name', 'email');
                    }
                ])
                ->where('end_date', null)
                ->where('status', 1)
                ->select(['vehicle_id', 'user_id', 'deliver_date'])
                ->get();

            $company_head = '';
            $head = User::on($portalDb)
                ->where('is_employee', 1)
                ->where('status', 1)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'Admin');
                })
                ->first();
            if (!empty($head)) {
                $company_head = getUserData($head);
            }

            $grievances = Grievance::on($portalDb)->get();
            $userLeaves = UserLeave::on($portalDb)->get();
            $employeeLetters = EmployeeLetter::on($portalDb)->get();
            $pre_employees = PreEmployee::on($portalDb)->where('form_type', 1)->select(['id', 'manager_id', 'name', 'father_name', 'email', 'contact_no', 'status', 'created_at', 'is_exist'])->get();
            $attendanceAdjustment = AttendanceAdjustment::on($portalDb)->select('id', 'created_by', 'employee_id', 'attendance_id', 'mark_type', 'created_at')->get();
            $discrepancies = Discrepancy::on($portalDb)->select('id', 'approved_by', 'user_id', 'attendance_id', 'date', 'type', 'description', 'status', 'is_additional', 'created_at')->get();
            $settings['company_id'] = $settings->company_id ?? 0;
            $settings['vehicles'] = $vehicleUsers;
            $settings['vehicle_percent'] =  (count($vehicleUsers) != 0 && count($total_employees) != 0) ? number_format(count($vehicleUsers) / count($total_employees) * 100) : 0;
            $settings['portalDb'] = $portalDb;
            $settings['total_employees'] = $total_employees;
            $settings['total_new_hiring'] = $total_new_hiring;
            $settings['total_terminated_employees'] = $total_terminated_employees;
            $settings['total_terminated_of_current_month'] = $terminatedUsersOfCurrentMonth;
            $settings['head'] = $company_head;
            $settings['base_url'] = $settings->base_url;
            $settings['company_key'] = $portalName;
            $settings['pre_employees'] =  $pre_employees;
            $settings['total_grievances'] = $grievances;
            $settings['user_leaves'] = $userLeaves;
            $settings['employee_letters'] = $employeeLetters;
            $settings['attendance_adjustment'] = $attendanceAdjustment;
            $settings['discrepancies'] = $discrepancies;
            $companies[$portalName] = $settings;
        } else {
            dd("Failed to Load Settings");
        }
    }

    return $companies;
}

//Get Companies & Employees
function getAllCompaniesEmployees()
{
    return getEmployees();
}
function getCompanyEmployees($companyName = null)
{
    return getEmployees($companyName);
}


function companyEmployee($company_title)
{

    $employees = [];
    foreach (getAllCompanies() as $company) {
        if ($company_title != null && $company_title == $company->portalDb) {
            $employees = User::on($company->portalDb)->with('profile')->where('is_employee', 1)->get();
        }
    }
    return $employees;
}

function getEmployees($companyName = null)
{

    $data = [];
    $allEmployees = [];
    $total_employees_count = 0;
    foreach (getAllCompanies() as $company) {

        if ($companyName != null && $companyName == $company->portalDb) {
            $total_employees_count += count($company->total_employees);
            foreach ($company->total_employees as $employee) {
                $allEmployees[] = (object) employeeDetails($company, $employee);
            }
            break;
        } elseif ($companyName == NULL) {
            $total_employees_count += count($company->total_employees);

            foreach ($company->total_employees as $employee) {
                $allEmployees[] = (object) employeeDetails($company, $employee);
            }
        }
    }

    $data['total_employees_count'] = $total_employees_count;
    $data['total_employees'] =  $allEmployees;

    return $data;
}

function getPreEmployees($companyName = null)
{

    $data = [];
    $allEmployees = [];
    $pre_employees_count = 0;
    foreach (getAllCompanies() as $company) {
        if ($companyName != null && $companyName ==  $company->portalDb) {
            $pre_employees_count += count($company->pre_employees);
            foreach ($company->pre_employees as $employee) {
                $allEmployees[] = (object) preEmployeeDetails($company, $employee);
            }
            break;
        } elseif ($companyName == NULL) {
            $pre_employees_count += count($company->pre_employees);

            foreach ($company->pre_employees as $employee) {
                $allEmployees[] = (object) preEmployeeDetails($company, $employee);
            }
        }
    }


    $data['pre_employees'] =  $allEmployees;

    return $data;
}
//Get Companies & Employees

//Get Vehicles & Employees
function getAllCompaniesVehicles()
{
    return getVehicles();
}

function getCompanyVehicles($companyName = null)
{
    return getVehicles($companyName);
}

function getVehicles($companyName = null)
{

    $data = [];
    $allCompaniesVehicles = [];

    foreach (companies() as $portalName => $portalDb) {
        if ($companyName != null && $companyName == $portalName) {
            $vehicleUsers = VehicleUser::on($portalDb)
                ->with([
                    'hasVehicle' => function ($query) {
                        $query->select('id', 'owner_id', 'name', 'thumbnail', 'model_year', 'engine_capacity');
                        $query->with('hasImage');
                    },
                    'hasUser'
                ])
                ->where('end_date', null)
                ->where('status', 1)
                ->select(['vehicle_id', 'user_id', 'deliver_date'])
                ->get();
            $setting['vehicles'] = $vehicleUsers;
            $setting['total_employees'] = count($portalDb->total_employees);
            $setting['base_url'] = $portalDb->base_url;
            $setting['company'] = $portalDb->name;

            $allCompaniesVehicles[$portalName] = $setting;

            break;
        } elseif ($companyName == null) {
            $vehicleUsers = VehicleUser::on($portalDb->portalDb)
                ->with([
                    'hasVehicle' => function ($query) {
                        $query->select('id', 'owner_id', 'name', 'thumbnail', 'model_year', 'engine_capacity');
                        $query->with('hasImage');
                    },
                    'hasUser.departmentBridge'
                ])
                ->where('end_date', null)
                ->where('status', 1)
                ->select(['vehicle_id', 'user_id', 'deliver_date'])
                ->get();

            $setting['vehicles'] = $vehicleUsers;
            $setting['total_employees'] = count($portalDb->total_employees);
            $setting['base_url'] = $portalDb->base_url;
            $setting['company'] = $portalDb->name;

            $allCompaniesVehicles[$portalName] = $setting;
        }
    }
    $vehicles = [];
    $totalEmployees = 0;
    foreach ($allCompaniesVehicles as $companyVehicles) {
        $totalEmployees += $companyVehicles['total_employees'];
        foreach ($companyVehicles['vehicles'] as $companyVehicle) {
            $profile = '';
            $employment_id = '-';
            if (isset($companyVehicle->hasUser->profile) && !empty($companyVehicle->hasUser->profile->profile)) {
                $profile = $companyVehicle->hasUser->profile->profile;
                $employment_id = $companyVehicle->hasUser->profile->employment_id;
            }
            $designation = '-';
            if (isset($companyVehicle->hasUser->jobHistory->designation->title) && !empty($companyVehicle->hasUser->jobHistory->designation->title)) {
                $designation = $companyVehicle->hasUser->jobHistory->designation->title;
            }
            $vehicleName = '';
            $vehicleThumbnail = '';
            $vehicleModelYear = '';
            $vehicleCc = '';
            if (isset($companyVehicle->hasVehicle) && !empty($companyVehicle->hasVehicle->name)) {
                $vehicleName = $companyVehicle->hasVehicle->name;
                $vehicleThumbnail = $companyVehicle->hasVehicle->thumbnail;
                $vehicleCc = $companyVehicle->hasVehicle->engine_capacity;
                $vehicleModelYear = $companyVehicle->hasVehicle->model_year;
            }

            $department = "";
            if (isset($companyVehicle->hasUser) && !empty($companyVehicle->hasUser)) {
                $department =  !empty($companyVehicle->hasUser->departmentBridge->department) ? $companyVehicle->hasUser->departmentBridge->department->name : "";
            }
            $shift = '-';
            if (isset($companyVehicle->hasUser->userWorkingShift->workShift) && !empty($companyVehicle->hasUser->userWorkingShift->workShift->name)) {
                $shift = $companyVehicle->hasUser->userWorkingShift->workShift->name;
            }
            $employment_status = '-';
            if (isset($companyVehicle->hasUser->employeeStatusEndDateNull->employmentStatus) && !empty($companyVehicle->hasUser->employeeStatusEndDateNull->employmentStatus->name)) {
                if ($companyVehicle->hasUser->employeeStatusEndDateNull->employmentStatus->name == 'Terminated') {
                    $employment_status = 'Terminated';
                } elseif ($companyVehicle->hasUser->employeeStatusEndDateNull->employmentStatus->name == 'Permanent') {
                    $employment_status = 'Permanent';
                } elseif ($companyVehicle->hasUser->employeeStatusEndDateNull->employmentStatus->name == 'Probation') {
                    $employment_status = 'Probation';
                } else {
                    $employment_status = $companyVehicle->hasUser->employeeStatusEndDateNull->employmentStatus->name;
                }
            }

            $vehicles[] = (object)[
                'base_url' => $companyVehicles['base_url'],
                'company' => $companyVehicles['company'],
                'avatar_path' => '/public/admin/assets/img/avatars/',
                'profile' => $profile,
                'employment_id' => $employment_id,
                'name' => $companyVehicle->hasUser->first_name . ' ' . $companyVehicle->hasUser->last_name,
                'designation' => $designation,
                'vehicleName' => $vehicleName,
                'vehicleThumbnail' => $vehicleThumbnail,
                'vehicleModelYear' => $vehicleModelYear,
                'vehicleCc' => $vehicleCc,
                'department' => $department,
                'shift' => $shift,
                'employment_status' => $employment_status
            ];
        }
    }
    $data['vehicles'] = $vehicles;
    $data['totalEmployees'] = $totalEmployees;

    return $data;
}

function getCompanyAttendance($companyName = null, $date = null)
{
    $data = [];
    $attendance_detail = [];
    $current_date = Carbon::now();
    // Set the current date to the 26th of the previous month
    $startDate = $current_date->subMonth()->setDay(26)->toDateString();
    // Set the current date to the 25th of the current month
    $endDate = $current_date->startOfMonth()->addDay(24)->toDateString();
    if ($date == null) {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
    } else {
        $dateParts = explode('/', $date);
        $month = $dateParts[0]; // Start date
        $year = $dateParts[1]; // End date

    }

    foreach (companies() as $portalName => $portalDb) {
        if ($companyName != null && $companyName == $portalDb) {

            $attendances = AttendanceSummary::on($companyName)->with([
                'attendance' => function ($query) {
                    $query->select('id', 'behavior');
                },
                'userShift'

            ])
                ->whereRaw('MONTH(in_date) = ?', [$month])->whereRaw('YEAR(in_date) = ?', [$year])
                ->get();
            $company = $portalDb->name;
        }
    }

    if (isset($attendances) && !empty($attendances)) {
        foreach ($attendances as $attendance) {
            $attendance_detail[] = (object)[
                'user_id' => $attendance->user_id,
                'behavior' => !empty($attendance->attendance) ? $attendance->attendance->behavior : "",
                'shift' => !empty($attendance->userShift) ? $attendance->userShift->name : "",
                'in_date' => $attendance->in_date,
                'out_date' => $attendance->out_date,
                'status' =>   $attendance->attendance_type,
                'company' => $company

            ];
        }
    }
    $data['attendance_detail'] = $attendance_detail;
    return $data;
}
//Get Vehicles & Employees

function getTotalWorkingDays($company)
{

    $start_of_month = Carbon::now()->startOfMonth()->toDateString();
    $current_date = Carbon::now()->toDateString();
    $companies = getAllCompanies();
    $attendance = '';
    foreach ($companies as $portalName => $portalDb) {
        if (!empty($company) && $company == $portalDb->company_key) {
            $attendance = Attendance::on($portalDb->portalDb)->where('in_date', '>=', $start_of_month)->where('in_date', '<=', $current_date)->count();
        }
    }

    return $attendance;
}
function getDailyAttendanceReport()
{
    if (date('d') > 25) {
        $begin = new DateTime(date('Y') . "-" . ((int)date('m')) . "-26");
        $end = new DateTime(date('Y') . "-" . (int)date('m') . '-' . date('d'));
    } else {
        $begin = new DateTime(date('Y') . "-" . ((int)date('m')) - 1 . "-26");
        $end = new DateTime(date('Y') . "-" . (int)date('m') . '-' . date('d'));
    }

    $attendanceDailyData = [];
    $total_regular_employees = [];
    $total_late_in_employees = [];
    $total_half_days_employees = [];
    $total_absent_employees = [];

    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
        $day = date("D", strtotime($i->format("Y-m-d")));

        if ($day != 'Sat' && $day != 'Sun') {
            $next = date("Y-m-d", strtotime('+1 day ' . $i->format("Y-m-d")));
            $totalRegulars = 0;
            $totalLateIns = 0;
            $totalHalfDays = 0;
            $totalAbsents = 0;
            foreach (getAllCompanies() as $company) {
                $employee_ids = $company['total_employees']->pluck('id')->toArray();
                foreach (getEmployeesAttendanceCount($company['portalDb'], $employee_ids, $i->format("Y-m-d"), $next) as $key => $value) {
                    if ($key == 'total_regular') {
                        $totalRegulars += $value;
                    } elseif ($key == 'total_late_in') {
                        $totalLateIns += $value;
                    } elseif ($key == 'total_half_days') {
                        $totalHalfDays += $value;
                    } else {
                        $totalAbsents += $value;
                    }
                }
            }
            $total_regular_employees[] = $totalRegulars;
            $total_late_in_employees[] = $totalLateIns;
            $total_half_days_employees[] = $totalHalfDays;
            $total_absent_employees[] = $totalAbsents;
        }
    }

    $attendanceDailyData['today_regulars'] = end($total_regular_employees);
    $attendanceDailyData['monthly_regulars'] = $total_regular_employees;
    $attendanceDailyData['today_lateIns'] = end($total_late_in_employees);
    $attendanceDailyData['monthly_lateIns'] = $total_late_in_employees;
    $attendanceDailyData['today_hafDays'] = end($total_half_days_employees);
    $attendanceDailyData['monthly_hafDays'] = $total_half_days_employees;
    $attendanceDailyData['today_absents'] = end($total_absent_employees);
    $attendanceDailyData['monthly_absents'] = $total_absent_employees;

    return $attendanceDailyData;
}
function getEmployeesAttendanceCount($portalDb, $employees, $current_date, $next_date)
{
    $data = [];

    $attendanceSummaries = AttendanceSummary::on($portalDb)->whereIn('user_id', $employees)
        ->whereBetween('in_date', [$current_date, $next_date])
        ->get();

    $lateInCount = 0;
    $halfDayCount = 0;
    $regularCount = 0;

    foreach ($attendanceSummaries as $attendanceSummary) {
        if ($attendanceSummary->attendance_type === 'lateIn') {
            $lateInCount++;
        } elseif ($attendanceSummary->attendance_type === 'firsthalf' || $attendanceSummary->attendance_type === 'lasthalf') {
            $halfDayCount++;
        } elseif ($attendanceSummary->attendance_type === 'regular') {
            $regularCount++;
        }
    }

    $data['total_regular'] = $regularCount;
    $data['total_late_in'] = $lateInCount;
    $data['total_half_days'] = $halfDayCount;
    $data['total_absent'] = count($employees) - count($attendanceSummaries);

    return $data;
}
function getAllTerminatedEmployees()
{

    $data = [];
    $terminated_employees = 0;
    $all_terminated_employees = [];
    foreach (getAllCompanies() as $company) {
        $terminated_employees += count($company->total_terminated_employees);

        foreach ($company->total_terminated_employees as $employee) {
            $all_terminated_employees[] = (object) employeeDetails($company, $employee);
        }
    }

    $data['all_terminated_employees_count'] = $terminated_employees;
    $data['all_terminated_employees'] = $all_terminated_employees;

    return $data;
}

function getAllTerminatedEmployeesOfCurrentMonth()
{
    $data = [];
    $terminated_employees = 0;
    $all_terminated_employees = [];
    foreach (getAllCompanies() as $company) {
        $terminated_employees += count($company->total_terminated_of_current_month);
        foreach ($company->total_terminated_of_current_month as $employee) {
            $all_terminated_employees[] = (object) employeeDetails($company, $employee);
        }
    }

    $data['all_terminated_employees_of_current_month'] = $all_terminated_employees;

    return $data;
}

function getAllCompaniesNewHiring()
{
    $data = [];
    $new_hired_employees = 0;
    $all_new_hired_employees = [];
    foreach (getAllCompanies() as $company) {
        $new_hired_employees += count($company->total_new_hiring);
        foreach ($company->total_new_hiring as $employee) {
            $all_new_hired_employees[] = (object) employeeDetails($company, $employee);
        }
    }

    $data['all_new_hiring'] = $all_new_hired_employees;

    return $data;
}

function employeeDetails($company, $employee)
{
    $profile = '';
    $employment_id = '-';
    if (!empty($employee->profile->profile)) {

        $profile = $employee->profile->profile;
        $employment_id = $employee->profile->employment_id;
    }
    $designation = '-';
    if (isset($employee->jobHistory->designation->title) && !empty($employee->jobHistory->designation->title)) {
        $designation = $employee->jobHistory->designation->title;
    }
    $department = '-';
    if (isset($employee->departmentBridge->department) && !empty($employee->departmentBridge->department)) {
        $department = $employee->departmentBridge->department->name;
    }
    $shift = '-';
    if (isset($employee->userWorkingShift->workShift) && !empty($employee->userWorkingShift->workShift->name)) {
        $shift = $employee->userWorkingShift->workShift->name;
    }
    $employment_status = '-';
    if (isset($employee->employeeStatusEndDateNull->employmentStatus) && !empty($employee->employeeStatusEndDateNull->employmentStatus->name)) {
        if ($employee->employeeStatusEndDateNull->employmentStatus->name == 'Terminated') {
            $employment_status = 'Terminated';
        } elseif ($employee->employeeStatusEndDateNull->employmentStatus->name == 'Permanent') {
            $employment_status = 'Permanent';
        } elseif ($employee->employeeStatusEndDateNull->employmentStatus->name == 'Probation') {
            $employment_status = 'Probation';
        } else {
            $employment_status = $employee->employeeStatusEndDateNull->employmentStatus->name;
        }
    }
    $data = [
        'favicon' => $company->favicon,
        'company' => $company->name,
        'employment_id' => $employment_id,
        'slug' => $employee->slug,
        'base_url' => $company->base_url,
        'avatar_path' => '/public/admin/assets/img/avatars/',
        'profile' => $profile,
        'name' => $employee->first_name . ' ' . $employee->last_name,
        'email' => $employee->email,
        'role' => $employee->getRoleNames()->first(),
        'designation' => $designation,
        'department' => $department,
        'shift' => $shift,
        'employment_status' => $employment_status,
    ];



    return $data;
}

function preEmployeeDetails($company, $employee)
{
    $title = '-';
    if (isset($employee->hasAppliedPosition->hasPosition) && !empty($employee->hasAppliedPosition->hasPosition->title)) {
        $title = $employee->hasAppliedPosition->hasPosition->title;
    }
    $expected_salary = '-';
    if (isset($employee->hasAppliedPosition) && !empty($employee->hasAppliedPosition->expected_salary)) {
        $expected_salary  = $employee->hasAppliedPosition->expected_salary;
    }
    $is_exist = '-';
    if (isset($employee) && !empty($employee)) {
        $is_exist = $employee->is_exist;
    }
    $status = '-';
    if (isset($employee->status) && !empty($employee->status)) {
        if ($employee->status == '1') {
            $status = 'Approved';
        } elseif ($employee->status == '0') {
            $status = 'Pending';
        } elseif ($employee->status == '2') {
            $status = 'Rejected';
        }
    }
    $created_at = "";
    if (isset($employee->created_at) && !empty($employee->created_at)) {
        $created_at = $employee->created_at;
    }
    $manager_id = "";
    if (isset($employee->manager_id) && !empty($employee->manager_id)) {
        $manager_id = $employee->manager_id;
    }
    $data = [

        'company' => $company->name,
        'company_key' => $company->company_key,
        'title' => $title,
        'expected_salary' => $expected_salary,
        'is_exist' => $is_exist,
        'status' => $status,
        'created_at' => $created_at,
        'manager_id' => $manager_id,
        'employee' => $employee

    ];



    return $data;
}

function todayPresentEmployees()
{
    $today_regulars = getDailyAttendanceReport()['today_regulars'];
    $today_lateIns = getDailyAttendanceReport()['today_lateIns'];
    return $today_regulars + $today_lateIns;
}

function getCurrentWeekAttendance()
{
    $begin = new DateTime(date('Y') . "-" . ((int)date('m')) . "-05");
    $end = new DateTime(date('Y') . "-" . (int)date('m') . '-' . "09");

    $total_regular_employees = [];
    $total_late_in_employees = [];

    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
        $day = date("D", strtotime($i->format("Y-m-d")));

        if ($day != 'Sat' && $day != 'Sun') {
            $next = date("Y-m-d", strtotime('+1 day ' . $i->format("Y-m-d")));
            $totalRegulars = 0;
            $totalLateIns = 0;
            foreach (getAllCompanies() as $company) {
                $employee_ids = $company['total_employees']->pluck('id')->toArray();
                foreach (getEmployeesAttendanceCount($company['portalDb'], $employee_ids, $i->format("Y-m-d"), $next) as $key => $value) {
                    if ($key == 'total_regular') {
                        $totalRegulars += $value;
                    } elseif ($key == 'total_late_in') {
                        $totalLateIns += $value;
                    }
                }
            }
            $total_regular_employees[$i->format("Y-m-d")][] = $totalRegulars;
            $total_late_in_employees[$i->format("Y-m-d")][] = $totalLateIns;
        }
    }

    $attendanceDailyData['today_regulars'] = end($total_regular_employees);
    $attendanceDailyData['today_lateIns'] = end($total_late_in_employees);

    return $attendanceDailyData;
}

//for future backup functions
// function getAllEmployeesActuallySalary(){
//     $totalActuallySalary = 0;
//     foreach(getAllCompanies() as $company){
//         $pkrSalaryAmount = SalaryHistory::on($company['portalDb'])->where('currency_code', null)->where('end_date', null)
//         ->orderBy('id', 'desc')
//         ->sum('salary');

//         $otherSalaryAmount = SalaryHistory::on($company['portalDb'])->where('currency_code', '!=', null)->where('currency_code', '!=', 'PKR')->where('end_date', null)
//         ->orderBy('id', 'desc')
//         ->sum('conversion_amount');

//         $totalActuallySalary += $pkrSalaryAmount+$otherSalaryAmount;
//     }

//     return $totalActuallySalary;
// }

// function getAllEmployeePaidLastMonthSalary(){
//     $data = [];
//     $totalActuallySalary = 0;
//     $totalNetSalary = 0;
//     foreach(getAllCompanies() as $company){
//         // $pkrSalaryAmount = SalaryHistory::on($company['portalDb'])->where('currency_code', null)->where('end_date', null)
//         // ->orderBy('id', 'desc')
//         // ->sum('salary');

//         // $otherSalaryAmount = SalaryHistory::on($company['portalDb'])->where('currency_code', '!=', null)->where('currency_code', '!=', 'PKR')->where('end_date', null)
//         // ->orderBy('id', 'desc')
//         // ->sum('conversion_amount');

//         // $totalActuallySalary += $pkrSalaryAmount+$otherSalaryAmount;

//         // Determine the last month
//         // $lastMonth = Carbon::now()->subMonth()->format('m/Y');
//         $lastMonth = '01/2024';
//         // Fetch records for the last month
//         // $lastMonthRecords = MonthlySalaryReport::on($company['portalDb'])->where('month_year', '01/2024')->get();
//         $lastMonthRecords = MonthlySalaryReport::on($company['portalDb'])
//         ->where('month_year', $lastMonth)
//         ->select(['conversion_rate', 'actual_salary', 'car_allowance', 'net_salary'])
//         ->get();

//         foreach($lastMonthRecords as $lastMonthRecord){
//             if($lastMonthRecord->conversion_rate != 0){
//                 //calculation with rate
//                 $netSalary = $lastMonthRecord->net_salary*$lastMonthRecord->conversion_rate;

//                 $actualSalary = $lastMonthRecord->actual_salary*$lastMonthRecord->conversion_rate;
//                 $carAllowance = $lastMonthRecord->car_allowance*$lastMonthRecord->conversion_rate;
//                 //calculation with rate

//                 $totalActuallySalary += $actualSalary+$carAllowance;
//                 $totalNetSalary += $netSalary;
//             }else{
//                 $totalActuallySalary += $lastMonthRecord->actual_salary+$lastMonthRecord->car_allowance;
//                 $totalNetSalary += $lastMonthRecord->net_salary;
//             }

//         }

//         // return $totalActuallySalary.'-----'.$totalNetSalary;
//         return $data = [
//             'actual_salary' => $totalActuallySalary,
//             'net_salary' => $totalNetSalary,
//         ];

//         // return $lastMonthRecords;
//         // $data['actualSalary'] += $lastMonthRecords->total_actual_salary+$lastMonthRecords->total_car_allowance;
//         // $data['netSalary'] += $lastMonthRecords->total_actual_salary+$lastMonthRecords->total_net_salary;

//     }

//     return $totalActuallySalary.'------'.$totalNetSalary;
// }

function apiResponse($success = null, $data = null, $message = null, $code = null, $pagination = null)
{
    $response = (object)[
        "success" => $success,
        "data" => $data,
        "message" => $message,
        "code" => $code,
    ];
    if ($pagination !== null) {
        $response->pagination = $pagination;
    }
    return $response;
}


function setPermissionName($name = null, $permission = null)
{
    //  $permission = list , create , delete etc
    $name = preg_replace('/\s+/', '-', $name); // remove all empty spaces with -  sign 
    $permission_names = explode(' ', $name);
    $permission = str_replace(' ', '-', Str::lower($permission));
    $p_name = "";
    if (isset($permission_names) && count($permission_names) > 0) {
        foreach ($permission_names as  $i => $p) {
            if ($i == 0) {
                $p_name .=    $p;
            }
            if ($i > 0) {
                $p_name .= "-" .   $p;
            }
        }
        $p_name = Str::plural($p_name);
        $p_name = strtolower($p_name);
        $p_name = $p_name . "-" . $permission;
    }
    return $p_name;
    // $p_name .= " " . $permission;
    // $p_name = str_replace(" ", "-", $p_name);
    // dd($p_name);

    // return  $p_name;
    $lastIndex = count($permission_names) - 1;
    $permission_name = $name . "-" . $permission;
    $first[] = Str::plural($permission_names[0]);
    unset($permission_names[0]);
    $n = array_merge($first, $permission_names);
    $p_name = implode('-', $n);
    return $p_name;
}
function getWordInitial($word, $size = null, $font_size = null, $border_radius = null)
{
    if (!isset($size) || empty($size)) {
        $size = '50px';
    }
    if (!isset($font_size) || empty($font_size)) {
        $font_size = '16px';
    }
    if (!isset($border_radius) || empty($border_radius)) {
        $border_radius = '100%';
    }
    $wordStr = !empty($word) ? substr($word, 0, 1)  : "-";
    $initial = '<p style="width: ' . $size . ';height: ' . $size . ';border-radius:' . $border_radius . ' ;background:#' . random_color() . ';display: flex;align-items: center;justify-content: center;color:white;text-transform: uppercase;font-size: ' . $font_size . '; font-weight:600; margin:0;">' . $wordStr . '</p>';
    $html = "";
    $html .= '<div class="d-flex justify-content-start align-items-center user-name">';
    $html .=     '<div class="avatar-wrapper">';
    $html .=     ' <div class="avatar avatar-sm">';
    $html .=             $initial;
    $html .=     '  </div>';
    // $html .=         '</div><div class="d-flex flex-column">';
    // $html .=              '<span class="fw-semibold">  ' . $word . '</span>';

    $html .=     '</div>';
    $html .= '</div>';
    return $html;
}

function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}
function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function getSlug()
{
    return Str::random(30) . "-" . time() . "-" . Str::random(30);
}

function uploadSingleFile($file = null, $folder_name = null, $prefix = null, $old_image = null)
{
    $folder = public_path($folder_name);
    if (isset($old_image) && !empty($old_image) && file_exists($folder . "/" . $old_image)) {
        unlink($folder . "/" . $old_image);
    }

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }
    $name = $prefix . "-" .  Str::random(6) . time() . "." . $file->getClientOriginalExtension();
    $file->move($folder, $name);
    return $name;
}

function formatDate($date)
{
    $format = Carbon::parse($date);
    $format = $format->format('M d,Y');
    return $format;
}
function formatTime($date)
{
    $format = Carbon::parse($date);
    $formattedTime = Carbon::parse($date)->format('H:i:s');
    return $formattedTime;
}
function companyData()
{
    $array  = [
        ['id' => 1, 'name' => 'Cyberonix Consulting Limited', 'base_url' => config("project.cyberonix_base_url")],
        ['id' => 2, 'name' => 'Vertical Edge', 'base_url' => config("project.vertical_base_url")],
        ['id' => 3, 'name' => 'Braincell  Technology', 'base_url' => config("project.braincell_base_url")],
        ['id' => 4, 'name' => 'C-Level', 'base_url' => config("project.clevel_base_url")],
        ['id' => 5, 'name' => 'DELVE12', 'base_url' => config("project.delve12_base_url")],
        ['id' => 6, 'name' => 'HORIZONTAL', 'base_url' => config("project.horizontal_base_url")],
        ['id' => 7, 'name' => 'MERCURY', 'base_url' => config("project.mercury_base_url")],
        ['id' => 8, 'name' => 'MOMYOM', 'base_url' => config("project.momyom_base_url")],
        ['id' => 9, 'name' => 'SOFTNOVA', 'base_url' => config("project.softnova_base_url")],
        ['id' => 10, 'name' => 'SOFTFELLOW', 'base_url' => config("project.softfellow_base_url")],
        ['id' => 11, 'name' => 'SWYFTCUBE', 'base_url' => config("project.swyftcube_base_url")],
        ['id' => 12, 'name' => 'SWYFTZONE', 'base_url' => config("project.swyftzone_base_url")],
        ['id' => 13, 'name' => 'TECHCOMRADE', 'base_url' => config("project.techcombrade_base_url")],
        ['id' => 14, 'name' => 'ROCKET-FLARE-LABS', 'base_url' => config("project.rocketflare_base_url")],
    ];
    return $array;
}

function findBaseUrl($company_id)
{
    $companies = companyData();
    foreach ($companies as $company) {
        if ($company['id'] == $company_id) {
            return $company['base_url'];
        }
    }
}

function getUserName($id)
{
    $user = User::where('id', $id)->first();
    if (!empty($user)) {
        $user_name = $user->first_name . " " . $user->last_name;
        return $user_name;
    }
}

function getUserName2($user)
{
    if (!empty($user)) {
        $user_name = $user->first_name . " " . $user->last_name;
        return $user_name;
    }
}
function getDepartments()
{
    $connections = companies(); // Update with your actual connection names

    $departments = collect();

    foreach ($connections as $connectionName) {
        // Query the department table for departments
        // $departmentsFromThisConnection = DB::connection($connectionName)->table('departments')->pluck('name'); // Assuming the table name is 'departments'
        $departmentsFromThisConnection = Department::on($connectionName)->pluck('name');

        // Merge departments from this connection with the main collection
        $departments = $departments->merge($departmentsFromThisConnection);
    }

    // Return the combined collection of departments from all databases
    $uniqueDepartments = $departments->unique();
    return $uniqueDepartments;
}
function getShifts()
{
    $connections = companies(); // Update with your actual connection names
    $shifts = collect();
    foreach ($connections as $connectionName) {
        // Query the department table for departments
        // $shiftsFromThisConnection = DB::connection($connectionName)->table('work_shifts')->pluck('name'); // Assuming the table name is 'departments'
        $shiftsFromThisConnection = WorkShift::on($connectionName)->pluck('name');

        // Merge departments from this connection with the main collection

        $shifts = $shifts->merge($shiftsFromThisConnection);
    }
    $uniqueShifts = $shifts->unique();
    // Return the combined collection of departments from all databases

    return $uniqueShifts;
}

function getShift($shift)
{

    $working_shift = WorkShift::where('id', $shift)->first();
    if (!empty($working_shift)) {
        return $working_shift->name;
    }
}

function formatPermissionLabel($permission)
{
    if (!empty($permission)) {
        $permission = explode('-', $permission);
        $name = "";
        foreach ($permission as $index =>  $value) {
            if ($index != 0) {
                $name .= $value . " ";
            }
        }
        return Str::ucfirst($name);
    } else {
        return "-";
    }
}

function getEmployeeDetails($companyName, $employeeSlug)
{
    foreach (companies() as $portalName => $portalDb) {
        if ($companyName != null && $companyName == $portalName) {
            $data = [];
            $user = User::on($portalDb)->where('slug', $employeeSlug)->first();
            $histories = SalaryHistory::on($portalDb)->orderby('id', 'desc')->where('user_id', $user->id)->get();
            $user_permanent_address = UserContact::on($portalDb)->where('user_id', $user->id)->where('key', 'permanent_address')->first();
            $user_current_address = UserContact::on($portalDb)->where('user_id', $user->id)->where('key', 'current_address')->first();
            $user_emergency_contacts = UserContact::on($portalDb)->where('user_id', $user->id)->where('key', 'emergency_contact')->get();
            $data['user'] = $user ?? '';
            $data['histories'] = $histories ?? '';
            $data['user_permanent_address'] = $user_permanent_address ?? '';
            $data['user_current_address'] = $user_current_address ?? '';
            $data['user_emergency_contacts'] = $user_emergency_contacts ?? '';
            if (isset($data) && !blank($data)) {
                return $data;
            } else {
                return 'No Record Found...!';
            }
        }
    }
}
function getEmpImage($base_url, $path, $image)
{
    $image = $base_url . $path . $image;
    return resize($image);
}

function resize($image = null, $array = null)
{

    if (!isset($array) || empty($array)) {
        $array = ['w' => 256, 'h' => 256];
    }




    if (config("app.mode") == "live") {
        $basePath = "://cbnslgndba.cloudimg.io/";
        $make_path = "";
        if (isset($image) && !empty($image)) {
            $image = explode("://", $image);
            $first = reset($image);
            $last = end($image);
            $make_path = $first . $basePath . $last;

            if (isset($array) && !empty($array)) {
                $make_path = $first . $basePath . $last . "?" . http_build_query($array);
            }
        }
        return $make_path;
    } else {
        return $image;
    }
}

function getUser($user_id = null, $company = null)
{
    if (isset($user_id) && !empty($user_id)) {
        $user_id = $user_id;
    } else {
        $user_id = Auth::user()->id;
    }

    $user = User::with("profile")->where('id', $user_id)->where('status', 1)->with('roles')->first();

    if (!empty($user)) {
        return $user;
    }
}
function getMyRole()
{
    $roles =  getUser()->roles->pluck("name")->toArray();
    return $roles[0];
}

function hasExceededLeaveLimit($user, $company)
{
    // $probation = UserEmploymentStatus::where('user_id', $user->id)->first();


    $probation = !empty($user) ? $user->employeeStatus : null;
    $total_used_leaves = 0;

    if (!empty($probation) && $probation->employment_status_id == 1) {
        $leave_report = [
            'total_leaves' => 0,
            'total_remaining_leaves' => 0,
            'total_leaves_in_account' => 0,
            'total_used_leaves' => 0,
            'leaves_in_balance' => 0,
        ];

        return $leave_report;
    } else {
        // Calculate the start and end dates of the current leave year
        // $currentYear = Carbon::now()->year;
        // $leaveYearStart = Carbon::createFromDate($currentYear, 6, 26); // June 26th of the current year
        // $leaveYearEnd = Carbon::createFromDate($currentYear + 1, 7, 25); // June 25th of the next year

        // Calculate the total used leaves within the leave year

        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalName) {


                $total_used_leaves = UserLeave::on($portalDb)->where('user_id', $user->id)
                    ->where('status', 1)
                    ->whereBetween('start_at', [yearPeriod()['yearStart'], yearPeriod()['yearEnd']])
                    ->sum('duration');
            }
        }


        // Calculate the number of months from the start date to the current date
        $currentDate = Carbon::now();
        if (!empty($user->employeeStatus->end_date)) {
            if (date('Y-m-d', strtotime($user->employeeStatus->end_date)) <= date('Y-m-d', strtotime($currentDate))) {
                $currentDate = $user->employeeStatus->end_date;
            }
        }
        $leaveYearStart = Carbon::createFromDate(yearPeriod()['yearStart']); // June 26th of the current year
        $monthsElapsed = $leaveYearStart->diffInMonths($currentDate) + 1;
        $leaveYearDate = Carbon::createFromDate(yearPeriod()['yearEnd']); // June 26th of the current year

        // Check if the user joined after the leave year started
        // $joiningDate = Carbon::createFromDate($user->employeeStatus->start_date); // Replace with the actual joining date
        $joiningDate = getUserJoiningDate($user); // Replace with the actual joining date

        if ($joiningDate > $leaveYearStart) {
            $monthsElapsed = max(0, $joiningDate->diffInMonths($currentDate)) + 1;
            $interval = $joiningDate->diff($leaveYearDate);
            $monthsDifference = ($interval->y * 12) + $interval->m;
            $total_leaves = $monthsDifference * 2;
        } else {
            $interval = $leaveYearStart->diff($leaveYearDate);
            $monthsDifference = ($interval->y * 12) + $interval->m;
            $total_leaves = $monthsDifference * 2;
        }


        $total_leaves_in_account = $monthsElapsed * 2;

        // Calculate the leave balance
        $leaves_in_balance = $total_leaves - $total_used_leaves;
        if ((float) $total_used_leaves >= (float) $total_leaves) {
            $leaves_in_balance = 0;
            $total_used_leaves = $total_leaves;
        }
        // if ((float) $total_used_leaves >=  (float) $total_leaves) {
        //     $total_used_leaves =  $total_leaves;
        // }

        $leave_report = [
            'total_leaves' => $total_leaves,
            'total_remaining_leaves' => $total_leaves - $total_used_leaves,
            'total_leaves_in_account' => $total_leaves_in_account,
            'total_used_leaves' => $total_used_leaves,
            'leaves_in_balance' => $leaves_in_balance,
        ];

        return $leave_report;
    }
}

function yearPeriod()
{
    $currentDate = Carbon::now();

    // Determine the leave start date based on the current date
    if ($currentDate->gte(Carbon::createFromDate($currentDate->year, 6, 26))) {
        // Leave tenure starts from June 26 of the current year
        $leaveStartDate = Carbon::createFromDate($currentDate->year, 6, 26);
    } else {
        // Leave tenure starts from June 26 of the previous year
        $leaveStartDate = Carbon::createFromDate($currentDate->year - 1, 6, 26);
    }

    $yearPeriod = [];
    // Add one year to get the end date
    $leaveEndDate = $leaveStartDate->copy()->addYear()->subDay()->addMonth(); // Adjusted for July 25
    $leaveYearStart = $leaveStartDate->toDateString();
    $leaveYearEnd = $leaveEndDate->toDateString();

    $yearPeriod['yearStart'] = $leaveYearStart;
    $yearPeriod['yearEnd'] = $leaveYearEnd;

    return $yearPeriod;
}

function getUserJoiningDate($user)
{

    if (isset($user->profile) && !empty($user->profile->joining_date)) {
        $start_date = $user->profile->joining_date;
        return $start_date = Carbon::parse($start_date);
    } else {
        return null;
    }
}


function getMonthDaysForSalary($year = null, $month = null)
{
    if (empty($year)) {
        $year = Carbon::now()->year;
    }
    if (empty($month)) {
        $month = Carbon::now()->month - 1;
    } else {
        $month  = $month - 1;
    }
    $firstDayOfMonth = Carbon::createFromDate($year, $month, 26); //26 to till 25 of next month in between days of these two dates.
    $lastDayOfMonth = $firstDayOfMonth->copy()->addMonth()->subDay();

    $totalDays = $firstDayOfMonth->diffInDays($lastDayOfMonth) + 1;
    // Initialize an array to store the result
    $filteredDays = [];
    // Loop through each day between the start and end dates
    $currentDay = $firstDayOfMonth->copy();
    while ($currentDay <= $lastDayOfMonth) {
        // Check if the current day is not a weekend (Saturday or Sunday)
        if (!$currentDay->isWeekend()) {
            // Add the current day to the result array
            $filteredDays[] = $currentDay->toDateString();
        }
        // Move to the next day
        $currentDay->addDay();
    }
    $totalDaysWithoutWeekends = count($filteredDays);
    $array = (object) [
        "first_date" => $firstDayOfMonth->toDateString(),
        "last_date" => $lastDayOfMonth->toDateString(),
        "fifth_date" => getFifthDateOfMonth($year, $month + 1),
        "total_days" => $totalDays,
        "total_days_without_weekends" => $totalDaysWithoutWeekends,
        "month" => $month + 1,
        "year" => $year,
        "monthYear" => $month . "/" . $year,
    ];
    return $array;
}

function getFifthDateOfMonth($year, $month)
{
    // Set the month and day to 1 to get the first day of the month
    $firstDayOfMonth = Carbon::create($year, $month, 1);

    // Add 4 days to get the 5th day of the month
    $fifthDate = $firstDayOfMonth->addDays(4);

    return $fifthDate->toDateString();
}

function getUserShift($user, $attendanceDate, $company)
{

    foreach (companies() as $portalName => $portalDb) {
        if ($company != null && $company == $portalName) {
            $shift = WorkingShiftUser::on($portalDb)->where('user_id', $user->id)->where('start_date', '<=', $attendanceDate)
                ->where(function ($query) use ($attendanceDate) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', $attendanceDate);
                })
                ->orderBy('start_date', 'desc')
                ->first();
        }
    }

    if (!empty($shift) && isset($shift->workShift) && !empty($shift->workShift)) {
        return $shift->workShift;
    } else {
        return defaultShift();
    }
}


function checkHoliday($employee_id, $holiday_date, $companyName)
{
    foreach (companies() as $portalName => $portalDb) {
        if ($companyName != null && $companyName == $portalDb) {
            $holiday = Holiday::on($companyName)->whereDate('start_at', '<=', $holiday_date)
                ->whereDate('end_at', '>=', $holiday_date)
                ->first();
        }
    }

    if (!empty($holiday)) {
        if ($holiday->type == "universal") {
            return $holiday;
        } elseif ($holiday->type == "customizable") {
            foreach (companies() as $portalName => $portalDb) {
                if ($companyName != null && $companyName == $portalDb) {
                    $holidayCustomize = HolidayCustomizeEmployee::on($companyName)->where('holiday_id', $holiday->id)->where('employee_id', $employee_id)->first();
                }
            }
            if (!empty($holidayCustomize)) {
                return $holiday;
            }
        } else {
            return '';
        }
    } else {
        return '';
    }
}

function userLeaveApplied($user_id, $punch_date, $company)
{
    foreach (companies() as $portalName => $portalDb) {
        if ($company != null && $company == $portalName) {
            $user_leave =  UserLeave::on($portalDb)->where('user_id', $user_id)
                ->where('is_applied', 1)
                ->where('status', 1)
                ->whereDate('start_at', '<=', $punch_date)
                ->whereDate('end_at', '>=', $punch_date)
                ->first();
        }
    }

    if (empty($user_leave)) {
        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalName) {
                $user_leave =  UserLeave::on($portalDb)->where('user_id', $user_id)
                    ->where('is_applied', 1)
                    ->whereDate('start_at', '<=', $punch_date)
                    ->whereDate('end_at', '>=', $punch_date)
                    ->first();
            }
        }
    }

    if (!empty($user_leave)) {
        $check_date = Carbon::parse($punch_date);
        $start_at = Carbon::parse($user_leave->start_at);
        $end_at = Carbon::parse($user_leave->end_at);

        if ($check_date->between($start_at, $end_at)) {
            return $user_leave;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkAdjustedAttendance($userID, $current_date, $company)
{
    foreach (companies() as $portalName => $portalDb) {
        if ($company != null && $company == $portalName) {
            $punchIn = AttendanceAdjustment::on($portalDb)->where('employee_id', $userID)->where('attendance_id', $current_date)->first();
        }
    }
    if (empty($punchIn) || $punchIn->mark_type == 'absent') {
        return true;
    } else {
        return false;
    }
}

function userAppliedLeaveOrDiscrepency($user_id, $type, $start_at, $company)
{

    if ($type == 'absent' || $type == 'firsthalf' || $type == "lasthalf") {

        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalName) {
                $user_leave = UserLeave::on($portalDb)->where('user_id', $user_id)
                    ->where('is_applied', 1)
                    ->where('status', 1)
                    ->whereDate('start_at', '<=', $start_at)
                    ->whereDate('end_at', '>=', $start_at)
                    ->first();
            }
        }


        if (empty($user_leave)) {
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
                    $user_leave = UserLeave::on($portalDb)->where('user_id', $user_id)
                        ->where('is_applied', 1)
                        ->whereDate('start_at', '<=', $start_at)
                        ->whereDate('end_at', '>=', $start_at)
                        ->first();
                }
            }
        }

        if (!empty($user_leave)) {
            $check_date = Carbon::parse($start_at);
            $shift_start_at = Carbon::parse($user_leave->start_at);
            $shift_end_at = Carbon::parse($user_leave->end_at);

            if ($check_date->between($shift_start_at, $shift_end_at)) {
                return $user_leave;
            } else {

                foreach (companies() as $portalName => $portalDb) {

                    if ($company != null && $company == $portalName) {

                        $userleave = UserLeave::on($portalDb)->where('user_id', $user_id)->where('behavior_type', $type)->where('start_at', $start_at)->first();
                    }
                }
                return $userleave;
            }
        } else {
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
                    $userleave =  UserLeave::on($portalDb)->where('user_id', $user_id)->where('behavior_type', $type)->where('start_at', $start_at)->first();
                }
            }
            return $userleave;
        }
    } elseif ($type == 'lateIn' || $type = "earlyout") {
        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalName) {
                $discrepancy = Discrepancy::on($portalDb)->where('user_id', $user_id)->where('type', $type)->where('date', $start_at)->first();
            }
        }
        return $discrepancy;
    }
}


function checkAttendance($userID, $current_date, $next_date, $shift, $company)
{
    // $user = User::where('id', $userID)->first();

    $start_time = date("Y-m-d H:i:s", strtotime($current_date . ' ' . $shift->start_time));
    $end_time = date("Y-m-d H:i:s", strtotime($next_date . ' ' . $shift->end_time));

    $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
    $end = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));
    foreach (companies() as $portalName => $portalDb) {
        if ($company != null && $company == $portalName) {
            $punchIn = Attendance::on($portalDb)->where('user_id', $userID)->whereBetween('in_date', [$start, $end])->orderBy('in_date', 'asc')->first();
        }
    }
    if (empty($punchIn)) {
        return true;
    } else {
        return false;
    }
}

function checkAttendanceSummary($userID, $shiftStartTime, $shiftEndTime, $companyName)
{
    foreach (companies() as $portalName => $portalDb) {
        if ($companyName != null && $companyName == $portalName) {
            $if_found = AttendanceSummary::on($portalDb)->where('user_id', $userID)->whereBetween('in_date', [$shiftStartTime, $shiftEndTime])->first();
        }
    }
    if (!empty($if_found)) {
        return $if_found;
    } else {
        return null;
    }
}

function checkAttendanceByID($attendance_id, $companyName)
{
    foreach (companies() as $portalName => $portalDb) {
        if ($companyName != null && $companyName == $portalName) {
            $att = Attendance::on($portalDb)->where('id', $attendance_id)->first();
        }
    }
    $data = '';
    if (!empty($att)) {
        $data = $att;
    }

    return $data;
}

function attendanceAdjustment($employee_id, $attendance_id, $date, $companyName)
{
    foreach (companies() as $portalName => $portalDb) {
        if ($companyName != null && $companyName == $portalName) {
            $adjustment = AttendanceAdjustment::on($portalDb)->where('employee_id', $employee_id)
                ->where(function ($query) use ($attendance_id, $date) {
                    $query->where('attendance_id', $attendance_id)
                        ->orWhere('attendance_id', $date);
                })
                ->first();
        }
    }
    if (!empty($adjustment)) {
        return $adjustment;
    } else {
        return NULL;
    }
}

function checkEmployeeAllowToFillDiscrepancy($user)
{
    $probation = $user->employeeStatus;
    if (!empty($probation) && $probation->employment_status_id == 1) {
        if (settings()->allow_to_fill_discrepancies == 1) {
            return 1; //1=> true means employee has allow to fill discrepancies
        } else {
            return 0; //0=> false means employee not allow to fill discrepancies
        }
    } else {
        return 1; //1=> true means if employee is permanent he has allow to fill discrepancies.
    }
}
function getTeamMembers($user, $companyName)
{
    $department_ids = [];
    if ($user->hasRole('Admin')) {
        foreach (companies() as $portalName => $portalDb) {
            if ($companyName != null && $companyName == $portalName) {
                $department_ids = Department::on($portalDb)->where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
            }
        }
    } elseif ($user->hasRole('Department Manager')) {
        foreach (companies() as $portalName => $portalDb) {
            if ($companyName != null && $companyName == $portalName) {
                $manager_dept_ids = Department::on($portalDb)->where('manager_id', $user->id)->where('status', 1)->pluck('id')->toArray();
                $department_ids = array_unique(array_merge($department_ids, $manager_dept_ids));
                $child_departments = Department::on($portalDb)->whereIn('parent_department_id', $manager_dept_ids)->where('status', 1)->pluck('id')->toArray();
            }
        }
        if (!empty($child_departments) && count($child_departments) > 0) {
            $department_ids = array_unique(array_merge($department_ids, $child_departments));
        }
    } elseif ($user->hasRole('Employee')) {
        if (isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->id)) {
            $department_ids[] = $user->departmentBridge->department_id;
        }
    }
    foreach (companies() as $portalName => $portalDb) {
        if ($companyName != null && $companyName == $portalName) {
            $team_members = DepartmentUser::on($portalDb)->with('employeeStatus')->whereIn('department_id', $department_ids)->where('end_date', null)->pluck('user_id')->toArray();
        }
    }
    return User::whereIn('id', $team_members)->where('id', '!=', $user->id)->where('is_employee', 1)->where('status', 1)->select(['id', 'slug', 'first_name', 'last_name', 'email', 'status'])->get();
}

function getCompanyFromID($company_id)
{
    return Company::where("company_id", $company_id)->first();
}
function getCompanyBaseUrl($company_id)
{
    $url = '';
    if (!empty($company_id)) {
        if ($company_id == 1) { // Cyberonixe
            $url = config("project.cyberonix_base_url");
        } elseif ($company_id == 2) { //Vertical Edge
            $url = config("project.vertical_base_url");
        } elseif ($company_id == 3) { //Braincell  Technology
            $url = config("project.braincell_base_url");
        } elseif ($company_id == 4) { //C-Level
            $url = config("project.clevel_base_url");
        } elseif ($company_id == 5) { //DELVE12
            $url = config("project.deleve_base_url");
        } elseif ($company_id == 6) { //HORIZONTAL
            $url = config("project.horizental_base_url");
        } elseif ($company_id == 7) { //MERCURY
            $url = config("project.mercury_base_url");
        } elseif ($company_id == 8) { //MOMYOM
            $url = config("project.momyom_base_url");
        } elseif ($company_id == 9) { //SOFTNOVA
            $url = config("project.softnova_base_url");
        } elseif ($company_id == 10) { //SOFTFELLOW
            $url = config("project.softfellow_base_url");
        } elseif ($company_id == 11) { //SWYFTCUBE
            $url = config("project.swyftcube_base_url");
        } elseif ($company_id == 12) { //SWYFTZONE
            $url = config("project.swyftzone_base_url");
        } elseif ($company_id == 13) { //TECHCOMRADE
            $url = config("project.techcomrade_base_url");
        } elseif ($company_id == 14) { //ROCKET
            $url = config("project.rocket_base_url");
        }
        return $url;
    }
}

function getAttandanceSingleRecord($userID, $current_date, $next_date, $status, $shift, $company)
{


    foreach (companies() as $portalName => $portalDb) {
        if ($company != null && $company == $portalDb) {
            $user = User::on($portalDb)->where('id', $userID)->first();
        }
    }


    $beginDate = Carbon::parse($current_date);

    $start_date = '';
    if (isset($user) && !empty($user)) {
        if (getUserJoiningDate($user)) {
            $start_date = getUserJoiningDate($user);
        }
    }

    $punchIn = "";
    $punchOut = "";
    if ($shift->type == 'scheduled') {
        $scheduled = '(Flexible)';

        $shiftTiming = date("h:i A", strtotime($shift->start_time)) . ' - ' . date("h:i A", strtotime($shift->end_time)) . $scheduled;

        // $start_time = date("Y-m-d H:i:s", strtotime($current_date . ' ' . $shift->start_time));
        $shift_start_time = $shift->start_time;
        if ($shift->start_time == "00:00:00") {
            $shift_start_time = str_replace($shift->start_time, '24:00:00', $shift->start_time);
        }
        $start_time = date("Y-m-d H:i:s", strtotime($current_date . ' ' . $shift_start_time));
        $end_time = date("Y-m-d H:i:s", strtotime($next_date . ' ' . $shift->end_time));

        $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
        $end = date("Y-m-d H:i:s", strtotime('+10 hours ' . $end_time));


        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalDb) {
                $punchIn = Attendance::on($portalDb)->where('user_id', $userID)->whereBetween('in_date', [$start, $end])->where('behavior', 'I')->orderBy('in_date', 'asc')->first();
            }
        }
        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalDb) {
                $punchOut = Attendance::on($portalDb)->where('user_id', $userID)->whereBetween('in_date', [$start, $end])->where('behavior', 'O')->orderBy('in_date', 'desc')->first();
            }
        }


        $label = '-';
        $type = '';
        $workingHours = '-';
        $workingMinutes = 0;
        $checkSecond = true;
        $attendance_id = '';
        $checkOut = '';

        if ($punchIn != null) {
            $attendance_id = $punchIn->id;
            $punchInRecord = new DateTime($punchIn->in_date);
            $checkIn = $punchInRecord->format('h:i A');
            $label = '<span class="badge bg-label-success">Regular</span>';
            $type = 'regular';
        } else {
            $checkIn = '-';
        }

        if ($punchIn != null && $punchOut != null) {
            $h1 = new DateTime($punchIn->in_date);
            $h2 = new DateTime($punchOut->in_date);
            $diff = $h2->diff($h1);
            $workingHours = $diff->format('%H:%I');
            $workingMinutes = $diff->h * 60 + $diff->i;
        }
        // dd($workingMinutes);
        $requiredMinutes = 8 * 60 + 30;
        $seven_hours = 7 * 60;

        if ($punchOut != null) {
            if ($punchIn == null) {
                $attendance_id = $punchOut->id;
            }
            $punchOutRecord = new DateTime($punchOut->in_date);
            $checkOut = $punchOutRecord->format('h:i A');
            if ($workingMinutes < $seven_hours) {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                $type = 'lasthalf';
            } elseif ($workingMinutes > $seven_hours && $workingMinutes < $requiredMinutes) {
                $label = '<span class="badge bg-label-warning"><i class="far fa-dot-circle text-warning"></i> Early Out</span>';
                $type = 'earlyout';
            }
        } else {
            $checkOut = '-';
        }

        if (($punchIn != null && $punchOut == null)) {
            if (date('Y-m-d H:i') > date('Y-m-d H:i', strtotime($end))) {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                $type = 'lasthalf';
            } else {
                $checkOut = 'Not Yet';
            }
        } elseif (($punchIn == null && $punchOut != null)) {
            if (date('Y-m-d H:i') > date('Y-m-d H:i', strtotime($end))) {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                $type = 'firsthalf';
            }
        }

        $currentDatecheck = date('Y-m-d'); // Current date in 'Y-m-d' format
        $midnightTimestamp = strtotime($currentDatecheck . ' 00:00:00'); // Midnight timestamp

        if (($punchIn == null && $punchOut == null) && $beginDate->greaterThanOrEqualTo($start_date) && strtotime($current_date) < $midnightTimestamp) {
            $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Absent</span>';
            $type = 'absent';
            $attendance_date = $current_date;
            $checkIn = '-';
            $checkOut = '-';
        }

        $discrepancy = '';
        $discrepancyStatus = '';
        $applied_discrepancy = '';
        $attendance_date = '';

        if ($type == 'earlyout' && !empty($punchIn)) {
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalDb) {
                    $discrepancy_record = Discrepancy::on($portalDb)->where('attendance_id', $punchIn->log_id)->orWhereDate('date', date('Y-m-d', strtotime($punchIn->in_date)))->where('user_id', $userID)->first();
                }
            }
            if (!empty($discrepancy_record)) {
                $discrepancy = $discrepancy_record->type;
                $discrepancyStatus = $discrepancy_record->status;
                $applied_discrepancy = $discrepancy_record;
            } else {
                $attendance_date = $punchIn;
                $attendance_id = $attendance_date->id;
            }
        }

        $leave = '';
        $applied_leaves = '';
        $leaveStatus = '';
        $punch_date = '';

        if ($type == 'absent') {
            $punch_date = date('Y-m-d', strtotime($current_date));
        } elseif ($type == 'lasthalf') {
            $punch_date = date('Y-m-d', strtotime($current_date));
        }

        if (isset($punch_date) && $punch_date != '') {
            if (userLeaveApplied($userID, $punch_date, $company)) {
                $leaves = userLeaveApplied($userID, $punch_date, $company);
            } else {
                foreach (companies() as $portalName => $portalDb) {
                    if ($company != null && $company == $portalDb) {
                        $leaves = UserLeave::on($portalDb)->where('behavior_type', $type)->whereDate('start_at', $punch_date)->where('user_id', $userID)->first();
                    }
                }
            }
            if (!empty($leaves)) {
                $leave = $leaves->behavior_type;
                $leaveStatus = $leaves->status;
                $applied_leaves = $leaves;
            } else {
                if ($type == 'absent') {
                    $attendance_date = $current_date;
                } elseif ($type == "lasthalf" && $punchIn == '') {
                    $attendance_date = $current_date;
                }
            }
        }
    } else {
        $scheduled = '';

        $shiftTiming = date("h:i A", strtotime($shift->start_time)) . ' - ' . date("h:i A", strtotime($shift->end_time)) . $scheduled;

        $shift_start_time = $shift->start_time;
        if ($shift->start_time == "00:00:00") {
            $shift_start_time = str_replace($shift->start_time, '24:00:00', $shift->start_time);
        }
        $start_time = date("Y-m-d H:i:s", strtotime($current_date . ' ' . $shift_start_time));

        $end_time = date("Y-m-d H:i:s", strtotime($next_date . ' ' . $shift->end_time));
        $shift_start_time = date("Y-m-d h:i A", strtotime('+16 minutes ' . $start_time));

        $shift_end_time = date("Y-m-d h:i A", strtotime('-16 minutes ' . $end_time));

        $shift_start_halfday = date("Y-m-d h:i A", strtotime('+121 minutes ' . $start_time));
        $shift_end_halfday = date("Y-m-d h:i A", strtotime('-121 minutes ' . $end_time));
        $start = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
        $end = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));
        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalDb) {
                $punchIn = Attendance::on($portalDb)->where('user_id', $userID)->whereBetween('in_date',  [$start, $end])->where('behavior', 'I')->orderBy('in_date', 'asc')->first(); // 2023-12-27 00:28:42
            }
        }
        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalDb) {
                $punchOut = Attendance::on($portalDb)->where('user_id', $userID)->whereBetween('in_date', [$start, $end])->where('behavior', 'O')->orderBy('in_date', 'desc')->first(); // 2023-12-27 09:24:15
            }
        }


        $label = '-';
        $type = '';
        $workingHours = '-';
        $workingMinutes = 0;
        $checkSecondDiscrepancy = true;
        $checkSecond = true;
        $attendance_id = '';

        if ($punchIn != null) {
            $attendance_id = $punchIn->id;
            $punchInRecord = new DateTime($punchIn->in_date);
            $checkIn = $punchInRecord->format('h:i A');

            if (strtotime($punchIn->in_date) < strtotime($shift_start_time)) {
                $label = '<span class="badge bg-label-success">Regular</span>';
                $type = 'regular';
            } elseif (strtotime($punchIn->in_date) >= strtotime($shift_start_time) && strtotime($punchIn->in_date) <= strtotime($shift_start_halfday)) {
                $label = '<span class="badge bg-label-warning"><i class="far fa-dot-circle text-warning"></i> Late In</span>';
                $type = 'lateIn';
                $checkSecondDiscrepancy = false;
            } else {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                $type = 'firsthalf';
                $checkSecond = false;
                $checkSecondDiscrepancy = false;
            }
        } else {
            $checkIn = '-';
        }

        if ($punchOut != null) {

            if ($punchIn == null) {
                $attendance_id = $punchOut->id;
            }
            $punchOutRecord = new DateTime($punchOut->in_date);
            $checkOut = $punchOutRecord->format('h:i A');

            if ($checkSecondDiscrepancy && (strtotime($punchOut->in_date) < strtotime($shift_end_time) && strtotime($punchOut->in_date) > strtotime($shift_end_halfday))) {
                $label = '<span class="badge bg-label-warning"><i class="far fa-dot-circle text-warning"></i> Early Out</span>';
                $type = 'earlyout';
            } else if ($checkSecond && strtotime($punchOut->in_date) < strtotime($shift_end_halfday)) {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                $type = 'lasthalf';
            }
        } else {
            $checkOut = '-';
        }

        if ($punchIn != null && $punchOut != null) {

            $h1 = new DateTime($punchIn->in_date);
            $h2 = new DateTime($punchOut->in_date);
            $diff = $h2->diff($h1);
            $workingHours = $diff->format('%H:%I');
            $workingMinutes = $diff->h * 60 + $diff->i;
        }

        if (($punchIn != null && $punchOut == null)) {
            if (date('Y-m-d H:i') > date('Y-m-d H:i', strtotime($end))) {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                $type = 'lasthalf';
            } else {
                $checkOut = 'Not Yet';
            }
        } elseif (($punchIn == null && $punchOut != null)) {
            if (date('Y-m-d H:i') > date('Y-m-d H:i', strtotime($end))) {
                $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Half-Day</span>';
                $type = 'firsthalf';
            }
        }

        $current_time = date("H:i:s");
        $date_comparsion = '';
        if (strtotime($current_time) > strtotime("00:00:00") && strtotime($current_time) <= strtotime("01:00:00")) {
            $date_comparsion = $current_date < date('Y-m-d');
        } else {
            $date_comparsion = $current_date <= date('Y-m-d');
        }

        $currentDatecheck = date('Y-m-d'); // Current date in 'Y-m-d' format
        $midnightTimestamp = strtotime($currentDatecheck . '00:00:01'); // Midnight timestamp

        if (($punchIn == null && $punchOut == null) && date('Y-m-d h:i A') > $shift_start_time && $date_comparsion && $beginDate->greaterThanOrEqualTo($start_date) && strtotime($current_date) < $midnightTimestamp) {
            $label = '<span class="badge bg-label-danger"><i class="far fa-dot-circle text-danger"></i> Absent</span>';
            $type = 'absent';
            $attendance_date = $current_date;
            $checkIn = '-';
        }

        $discrepancy = '';
        $discrepancyStatus = '';
        $applied_discrepancy = '';
        $attendance_date = '';

        if ($type == 'lateIn' || $type == 'late' || $type == 'earlyout' && !empty($punchIn)) {
            $originalDate = $punchIn->in_date;
            $carbonDate = \Carbon\Carbon::parse($originalDate);
            // Subtract 1 hour
            $modifiedDate = $carbonDate->subHour();

            // Format the result as needed
            $result = \Carbon\Carbon::parse($start)->toDateString();

            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
                    $discrepancy_record = Discrepancy::on($portalDb)->whereDate('date',  $result)->where('user_id', $userID)->first();
                }
            }

            if (!empty($discrepancy_record)) {
                $discrepancy = $discrepancy_record->type;
                $discrepancyStatus = $discrepancy_record->status;
                $applied_discrepancy = $discrepancy_record;
            } else {
                $attendance_date = $punchIn;
                $attendance_id = $attendance_date->id;
            }
        }

        $leave = '';
        $applied_leaves = '';
        $leaveStatus = '';
        $punch_date = '';

        if ($type == 'absent') {
            $punch_date = date('Y-m-d', strtotime($current_date));
        } else if ($type == 'firsthalf') {
            $punch_date = date('Y-m-d', strtotime($current_date));
        } else if ($type == 'lasthalf') {
            $punch_date = date('Y-m-d', strtotime($current_date));
        }

        if (isset($punch_date) && $punch_date != '') {

            if (userLeaveApplied($userID, $punch_date, $company)) {
                $leaves = userLeaveApplied($userID, $punch_date, $company);
            } else {
                foreach (companies() as $portalName => $portalDb) {
                    if ($company != null && $company == $portalName) {
                        $leaves = UserLeave::on($portalDb)->where('behavior_type', $type)->whereDate('start_at', $punch_date)->where('user_id', $userID)->first();
                    }
                }
            }

            if (!empty($leaves)) {
                $leave = $leaves->behavior_type;
                $leaveStatus = $leaves->status;
                $applied_leaves = $leaves;
            } else {
                if ($type == 'absent') {
                    $attendance_date = $current_date;
                } elseif ($type == "lasthalf" && $punchIn == '') {
                    $attendance_date = $current_date;
                } elseif ($type == "lasthalf" && $punchIn != '') {
                    $attendance_date = $punchIn;
                    $attendance_id = $attendance_date->id;
                } elseif ($type == "firsthalf" && $punchIn == '') {
                    $attendance_date = $current_date;
                } elseif ($type == "firsthalf" && $punchIn != '') {
                    $attendance_date = $current_date;
                }
            }
        }
    }

    if ($type == 'regular') {
        $attendance_date = $punchIn;
        $attendance_id = $attendance_date->id;
    }

    $data = array(
        'punchIn' => $checkIn ?? null,
        'punchOut' => $checkOut ?? null,
        'label' => $label ?? null,
        'type' => $type ?? null,
        'shiftTiming' => $shiftTiming ?? null,
        'shiftType' => $shift->type ?? null,
        'workingHours' => $workingHours ?? null,
        'workingMinutes' => $workingMinutes ?? null,
        'discrepancy' => $discrepancy ?? null,
        'discrepancyStatus' => $discrepancyStatus ?? null,
        'applied_discrepancy' => $applied_discrepancy ?? null,
        'leave' => $leave ?? null,
        'leaveStatus' => $leaveStatus ?? null,
        'applied_leaves' => $applied_leaves ?? null,
        'attendance_date' => $attendance_date ?? null,
        'attendance_id' => $attendance_id ?? null,
        'user' => $user ?? null,
        'punch_in_id' => $punchIn->id ?? null,
        'punch_out_id' => $punchOut->id ?? null,
        'punch_in' => $punchIn ?? null,
        'punch_out' => $punchOut ?? null,
        'shift' => $shift ?? null,
    );

    if ($status == 'all') {
        return $data;
    } elseif ($status == 'regular' && $type == 'regular') {
        return $data;
    } elseif ($status == 'absent' && $type == 'absent') {
        return $data;
    } elseif ($status == 'lateIn' && $type == 'lateIn') {
        return $data;
    } elseif ($status == 'earlyout' && $type == 'earlyout') {
        return $data;
    } elseif ($status == 'halfday' && ($type == 'firsthalf' || $type == 'lasthalf')) {
        return $data;
    } else {
        return null;
    }
}

function getCurrencyCodeForSalary($user)
{
    if (!empty($user->salaryHistory) && !empty($user->salaryHistory->getCurrency)) {
        return $user->salaryHistory->getCurrency->symbol;
    } else {
        return "Rs.";
    }
}

function checkSalarySlipGenerationDate($data)
{
    $currentDate = new DateTime(); // Get the current date and time
    $currentDate->modify('-1 month'); // Go back one month
    $previousMonth = $currentDate->format('d'); // Format the date as needed

    if (($previousMonth == $data->month || $data->month == date('m')) && (date('d') >= 25 || date('d') <= 5)) {
        return true;
    } else {
        if ($data->first_date <  Carbon::now()->toDateString()  && $data->fifth_date >  Carbon::now()->toDateString()) {
            return true;
        } else {
            return false;
        }
    }
}

function getUserSalary($user, $month, $year, $company)
{
    // Get current date
    $currentDateTime = "$year-$month-" . date('d');
    $date = date('d');
    if (!empty($user->profile->joining_date)) {
        $joiningDate = $user->profile->joining_date;
        // Convert joining date string to Carbon instance
        $joiningDateTime = Carbon::createFromFormat('Y-m-d', $joiningDate);
        // Compare the dates
        if ($joiningDateTime->gt($currentDateTime)) {
            $date = date('d', strtotime($joiningDateTime));
        }
    }
    foreach (companies() as $index => $portalDb) {
        if (!empty($company) && $company == $index) {
            $userSalary = SalaryHistory::on($portalDb)->where('user_id', $user->id)
                ->where('effective_date', '<=', "$year-$month-" . $date)
                ->where(function ($query) use ($month, $year) {
                    $query->where('end_date', '>=', "$year-$month-" . date('d'))
                        ->orWhereNull('end_date');
                })
                ->orderBy('effective_date', 'desc')
                ->first();
        }
    }
    if (!empty($userSalary)) {
        return $userSalary->salary;
    } else {
        return 0;
    }
}
function getAttandanceCount($user_id, $year_month_pre, $year_month_post, $behavior, $shift, $company)
{
    return AttendanceController::getAttandanceCount($user_id, $year_month_pre, $year_month_post, $behavior, $shift, $company);
}

function grievancesDetail($companyName, $grievance)
{
    $id = '-';
    if (isset($grievance->id) && !empty($grievance->id)) {
        $id = $grievance->id;
    }
    $creator = '-';
    if (isset($grievance->creator_id) && !empty($grievance->creator_id)) {
        $creator = $grievance->hasCreator;
    }
    $user = '-';
    if (isset($grievance->user_id) && !empty($grievance->user_id)) {
        $user  = $grievance->hasUser ?? null;
        $profile  = $grievance->hasUser->profile ?? null;
    }
    $description = '-';
    if (isset($grievance) && !empty($grievance)) {
        $description = $grievance->description;
    }
    $anonymous = '-';
    if (isset($grievance->anonymous) && !empty($grievance->anonymous)) {
        if ($grievance->anonymous == '1') {
            $anonymous = 'Yes';
        } elseif ($grievance->anonymous == '2') {
            $anonymous = 'No';
        }
    }
    $status = '-';
    if (isset($grievance->status) && !empty($grievance->status)) {
        if ($grievance->status == '1') {
            $status = 'Active';
        } elseif ($grievance->status == '0') {
            $status = 'De-Active';
        }
    }
    $created_at = "";
    if (isset($grievance->created_at) && !empty($grievance->created_at)) {
        $created_at = date('F d, Y', strtotime($grievance->created_at));
    }
    $data = [
        'id' => $id,
        'company' => $companyName->name,
        'company_key' => $companyName->company_key,
        'creator' => $creator,
        'user' => $user,
        'user_profile' =>   $profile ?? null,
        'description' => $description,
        'anonymous' => $anonymous,
        'status' => $status,
        'created_at' => $created_at,
        'grievance' => $grievance,

    ];
    return $data;
}

function getGrievances($companyName = null)
{
    $data = [];
    $grievances = [];
    foreach (getAllCompanies() as $company) {
        if ($companyName != null && $companyName == $company->company_key) {
            foreach ($company->total_grievances as $grievance) {
                $grievances[] = (object) grievancesDetail($company, $grievance);
            }
            break;
        } elseif ($companyName == NULL) {
            foreach ($company->total_grievances as $grievance) {
                $grievances[] = (object) grievancesDetail($company, $grievance);
            }
        }
    }
    $data['grievances'] =  $grievances;
    return $data;
}

function getGrievanceDetail($id)
{
    foreach (companies() as $portalName => $portalDb) {
        $grievance = '';
        $grievance = Grievance::on($portalDb)->where('id', $id)->first();
        if (isset($grievance) && !blank($grievance)) {
            return $grievance;
        } else {
            return 'No Record Found...!';
        }
    }
}
if (!function_exists('humanReadableNumber')) {
    function humanReadableNumber($number, $precision = 1)
    {
        if ($number < 1000) {
            return $number;
        } elseif ($number < 1000000) {
            return round($number / 1000, $precision) . 'K';
        } elseif ($number < 1000000000) {
            return round($number / 1000000, $precision) . 'M';
        } else {
            return round($number / 1000000000, $precision) . 'B';
        }
    }
}

function userWithHtml($user)
{
    $resizeImage = resize(asset('public/admin/assets/img/avatars') . '/' .  $user->profile->profile, [
        "w" => 256,
        "h" => 256,
    ]);
    if (isset($user->profile->profile) && !empty($user->profile->profile)) {
        $image = '<img src="' . $resizeImage . '" alt="Avatar" class="rounded-circle img-avatar">';
    } else {
        $image = '<img src="' . asset('public/admin/default.png') .  '" alt="Avatar" class="rounded-circle img-avatar">';
    }
    $html = "";
    $html .= '<div class="d-flex justify-content-start align-items-center user-name"><div class="avatar-wrapper"><div class="avatar avatar-sm me-3">';
    $html .= $image ?? null;
    $html .= '</div></div><div class="d-flex flex-column">';
    $html .= '<a href="' . route('employees.show', $user->slug) . '" class="text-body text-truncate">';
    $html .= '<span class="fw-semibold"> ' . getUserName($user) . '  (' . $user->profile->employment_id  . ')</span>';
    $html .= '</a><small class="emp_post text-truncate text-muted">';
    $html .= !empty($user->jobHistory->designation->title) ? $user->jobHistory->designation->title : "-";
    $html .= '</small></div></div>';

    return $html;
}
// function getCompaniesDetails($companyName){
//     foreach (companies() as $portalName => $portalDb) {
//         if ($companyName != null && $companyName == $portalDb) {
//             $settings = Setting::on($portalDb)->select(['id', 'base_url', 'name', 'phone_number', 'email', 'favicon'])->first();

//             if (!empty($settings)) {
//                 $settings['portalDb'] = $portalDb;
//                 $settings['base_url'] = $settings->base_url;
//                 $settings['company_key'] = $portalName;
//                 $companies[$portalName] = $settings;

//                 return $companies;
//             } else {
//                 dd("Failed to Load Settings");
//             }
//         }
//     }
// }

function attendanceReport($portalDb)
{
    $salaryReports = MonthlySalaryReport::on($portalDb)
        ->select(
            'month_year',
            DB::raw('SUM(actual_salary) as total_actual_salary'),
            DB::raw('SUM(car_allowance) as total_car_allowance'),
            DB::raw('SUM(deduction) as total_deduction'),
            DB::raw('SUM(net_salary) as total_net_salary')
        )
        ->groupBy('month_year')
        ->get();

    return $salaryReports;
}

function getLeavesDetail($companyName, $leave){
    $id = '-';
    if (isset($leave->id) && !empty($leave->id)) {
        $id = $leave->id;
    }
    $user = '-';
    if (isset($leave->user_id) && !empty($leave->user_id)) {
        $user = $leave->hasEmployee;
    }
    $leave_type_id = '-';
    if (isset($leave->leave_type_id) && !empty($leave->leave_type_id)) {
        $leave_type_id  = $leave->leave_type_id;
    }
    $department_id = '-';
    if (isset($leave) && !empty($leave)) {
        $department_id = $leave->department_id;
    }
    $is_applied = '-';
    if (isset($leave->is_applied) && !empty($leave->is_applied)) {
        $is_applied = $leave->is_applied;
    }
    $start_at = "";
    if (isset($leave->start_at) && !empty($leave->start_at)) {
        $start_at = date('F d, Y',strtotime($leave->start_at));
    }
    $end_at = "";
    if (isset($leave->end_at) && !empty($leave->end_at)) {
        $end_at = date('F d, Y',strtotime($leave->end_at));
    }
    $duration = "";
    if (isset($leave->duration) && !empty($leave->duration)) {
        $duration = $leave->duration;
    }
    $behavior_type = "";
    if (isset($leave->behavior_type) && !empty($leave->behavior_type)) {
        $behavior_type = $leave->behavior_type;
    }
    $reason = "";
    if (isset($leave->reason) && !empty($leave->reason)) {
        $reason = $leave->reason;
    }
    $status = '-';
    if (isset($leave->status) && !empty($leave->status)) {
        if ($leave->status == '1') {
            $status = 'Approved';
        } elseif ($leave->status == '2') {
            $status = 'Rejected';
        }
    }else{
        $status = empty($leave->status) && $leave->status == 0 ? 'Pending' : '-'; 
    }
    $created_at = "";
    if (isset($leave->created_at) && !empty($leave->created_at)) {
        $created_at = date('F d, Y',strtotime($leave->created_at));
    }
    $data = [
        'id' => $id,
        'company' => $companyName->name,
        'company_key' => $companyName->company_key,
        'user' => $user,
        'leave_type_id' => $leave_type_id,
        'department_id' => $department_id,
        'is_applied' => $is_applied,
        'start_at' => $start_at,
        'end_at' => $end_at,
        'duration' => $duration,
        'behavior_type' => $behavior_type,
        'reason' => $reason,
        'status' => $status,
        'created_at' => $created_at,
        'leave' => $leave,
    ];
    return $data;
}

function getLeaves($companyName = null)
{
    $data = [];
    $leaves = [];
    foreach (getAllCompanies() as $company) {
        if ($companyName != null && $companyName == $company->company_key) {
            foreach ($company->user_leaves as $leave) {
                $leaves[] = (object) getLeavesDetail($company, $leave);
            }
            break;
        } elseif ($companyName == NULL) {
            foreach ($company->user_leaves as $leave) {
                $leaves[] = (object) getLeavesDetail($company, $leave);
            }
        }
    }
    $data['leaves'] =  $leaves;
    return $data;
}

function getUserLeaveDetail($id)
{
    foreach (companies() as $portalName => $portalDb) {
        $userLeave = '';
        $userLeave = UserLeave::on($portalDb)->where('id', $id)->first();
        if (isset($userLeave) && !blank($userLeave)) {
            return $userLeave;
        } else {
            return 'No Record Found...!';
        }
    }
}

function getEmployeesLettersDetail($companyName, $employeeLetter){
    
    $id = '-';
    if (isset($employeeLetter->id) && !empty($employeeLetter->id)) {
        $id = $employeeLetter->id;
    }
    $created_by = '-';
    if (isset($employeeLetter->created_by) && !empty($employeeLetter->created_by)) {
        $created_by = $employeeLetter->hasCreatedBy;
    }
    $hasEmployee = '-';
    if (isset($employeeLetter->hasEmployee) && !empty($employeeLetter->hasEmployee)) {
        $hasEmployee  = $employeeLetter->hasEmployee;
    }
    $salaryHistory = '-';
    if (isset($employeeLetter->hasEmployee->salaryHistory) && !empty($employeeLetter->hasEmployee->salaryHistory)) {
        $salaryHistory  = $employeeLetter->hasEmployee->salaryHistory;
    }
    $reporting_name = '-';
    if (isset($employeeLetter->hasEmployee->joiningDepartmentBridge->department->manager) && !empty($employeeLetter->hasEmployee->joiningDepartmentBridge->department->manager)) {
        $reporting_name  = $employeeLetter->hasEmployee->joiningDepartmentBridge->department->manager;
    }
    $hasTemplate = '-';
    if (isset($employeeLetter) && !empty($employeeLetter)) {
        $hasTemplate = $employeeLetter->hasTemplate;
    }
    $hasUserVehicle = '-';
    if (isset($employeeLetter->hasUserVehicle) && !empty($employeeLetter->hasUserVehicle)) {
        $hasUserVehicle = $employeeLetter->hasUserVehicle;
    }
    $title = "";
    if (isset($employeeLetter->title) && !empty($employeeLetter->title)) {
        $title = $employeeLetter->title;
    }
    $effective_date = "";
    if (isset($employeeLetter->effective_date) && !empty($employeeLetter->effective_date)) {
        $effective_date = date('d, M Y',strtotime($employeeLetter->effective_date));
    }
    $validity_date = "";
    if (isset($employeeLetter->validity_date) && !empty($employeeLetter->validity_date)) {
        $validity_date = date('d, M Y',strtotime($employeeLetter->validity_date));
    }
    $created_at = "";
    if (isset($employeeLetter->created_at) && !empty($employeeLetter->created_at)) {
        $created_at = date('d, M Y',strtotime($employeeLetter->created_at));
    }
    $data = [
        'id' => $id,
        'company' => $companyName->name,
        'company_key' => $companyName->company_key,
        'created_by' => $created_by,
        'hasEmployee' => $hasEmployee,
        'hasTemplate' => $hasTemplate,
        'salaryHistory' => $salaryHistory,
        'reporting_name' => $reporting_name,
        'hasUserVehicle' => $hasUserVehicle,
        'title' => $title,
        'effective_date' => $effective_date,
        'validity_date' => $validity_date,
        'created_at' => $created_at,
        'employeeLetter' => $employeeLetter,
    ];
    return $data;
}

function getEmployeesLetters($companyName = null)
{
    $data = [];
    $employeeLetters = [];
    foreach (getAllCompanies() as $company) {
        if ($companyName != null && $companyName == $company->company_key) {
            foreach ($company->employee_letters as $employeeLetter) {
                $employeeLetters[] = (object) getEmployeesLettersDetail($company, $employeeLetter);
            }
            break;
        } elseif ($companyName == NULL) {
            foreach ($company->employee_letters as $employeeLetter) {
                $employeeLetters[] = (object) getEmployeesLettersDetail($company, $employeeLetter);
            }
        }
    }
    $data['employee_letters'] =  $employeeLetters;
    return $data;
}

function getEmployeeLetterDetail($id)
{
    foreach (companies() as $portalName => $portalDb) {
        $employeeLetter = '';
        $employeeLetter = EmployeeLetter::on($portalDb)->where('id', $id)->first();
        if (isset($employeeLetter) && !blank($employeeLetter)) {
            return $employeeLetter;
        } else {
            return 'No Record Found...!';
        }
    }
}

function formatLetterTitle($text)
{
    // Remove underscores and replace with spaces
    $textWithoutUnderscores = str_replace('_', ' ', $text);

    // Capitalize the first character of each word
    $formattedText = ucwords($textWithoutUnderscores);

    return $formattedText;
}

function getIpRestriction()
{
    return config("app.ip_restrict");
}

function getCompanySettings($companyName){
    foreach (companies() as $portalName => $portalDb) {
        $settings = Setting::on($portalDb)->select('*')->where('name', $companyName)->first();
    }
    return $settings;
}

function hrName(){
    foreach (companies() as $portalName => $portalDb) {
        $department = Department::on($portalDb)->select('*')->where('name', 'like', '%Admin%')->where('manager_id', '!=', NULL)->where('status', 1)->first();
        if(!empty($department) && !empty($department->manager)){
            $manager_full_name = $department->manager->first_name.' '.$department->manager->last_name;
        }else{
            $manager_full_name = 'N/A';
        }
    }
    return $manager_full_name;
}

function getAttendanceAdjustments($companyName = null){
    $data = [];
    $attendanceAdjustments = [];
    foreach (getAllCompanies() as $company) {
        if ($companyName != null && $companyName == $company->company_key) {
            foreach ($company->attendance_adjustment as $attendance_adjustment) {
                $attendanceAdjustments[] = (object) getAttendanceAdjustmentDetail($company, $attendance_adjustment);
            }
            break;
        } elseif ($companyName == NULL) {
            foreach ($company->attendance_adjustment as $attendance_adjustment) {
                $attendanceAdjustments[] = (object) getAttendanceAdjustmentDetail($company, $attendance_adjustment);
            }
        }
    }
    $data['attendance_adjustment'] =  $attendanceAdjustments;
    return $data;
}

function getAttendanceAdjustmentDetail($companyName, $attendance_adjustment){
    $id = '-';
    if (isset($attendance_adjustment->id) && !empty($attendance_adjustment->id)) {
        $id = $attendance_adjustment->id;
    }
    $hasEmployee = '-';
    if (isset($attendance_adjustment->hasEmployee) && !empty($attendance_adjustment->hasEmployee)) {
        $hasEmployee  = $attendance_adjustment->hasEmployee;
    }
    $hasAttendance = '-';
    if (isset($attendance_adjustment->hasAttendance) && !empty($attendance_adjustment->hasAttendance)) {
        $hasAttendance  = date('d, M Y',strtotime($attendance_adjustment->hasAttendance->in_date));
    }
    $mark_type = '-';
    if (isset($attendance_adjustment->mark_type) && !empty($attendance_adjustment->mark_type)) {
        $mark_type = $attendance_adjustment->mark_type;
    }
    $created_at = "";
    if (isset($attendance_adjustment->created_at) && !empty($attendance_adjustment->created_at)) {
        $created_at = date('d, M Y',strtotime($attendance_adjustment->created_at));
    }
    $data = [
        'id' => $id,
        'company' => $companyName->name,
        'company_key' => $companyName->company_key,
        'hasEmployee' => $hasEmployee,
        'mark_type' => $mark_type,
        'hasAttendance' => $hasAttendance,
        'created_at' => $created_at,
        'attendance_adjustment' => $attendance_adjustment,
    ];
    return $data;
}

function getDiscrepancies($companyName = null){
    $data = [];
    $discrepancies = [];
    foreach (getAllCompanies() as $company) {
        if ($companyName != null && $companyName == $company->company_key) {
            foreach ($company->discrepancies as $discrepancie) {
                $discrepancies[] = (object) getdiscrepanciesDetail($company, $discrepancie);
            }
            break;
        } elseif ($companyName == NULL) {
            foreach ($company->discrepancies as $discrepancie) {
                $discrepancies[] = (object) getdiscrepanciesDetail($company, $discrepancie);
            }
        }
    }
    $data['discrepancies'] =  $discrepancies;
    return $data;
}

function getDiscrepanciesDetail($companyName, $discrepancie){
    $id = '-';
    if (isset($discrepancie->id) && !empty($discrepancie->id)) {
        $id = $discrepancie->id;
    }
    $hasEmployee = '-';
    if (isset($discrepancie->hasEmployee) && !empty($discrepancie->hasEmployee)) {
        $hasEmployee  = $discrepancie->hasEmployee;
    }
    $hasAttendance = '-';
    if (isset($discrepancie->hasAttendance) && !empty($discrepancie->hasAttendance)) {
        $hasAttendance  = date('d, M Y',strtotime($discrepancie->hasAttendance->in_date));
    }
    $approved_by = '-';
    if (isset($discrepancie->approved_by) && !empty($discrepancie->approved_by)) {
        $approved_by = $discrepancie->approved_by;
    }
    $date = "";
    if (isset($discrepancie->date) && !empty($discrepancie->date)) {
        $date = date('d, M Y',strtotime($discrepancie->date));
    }
    $type = "";
    if (isset($discrepancie->type) && !empty($discrepancie->type)) {
        $type = $discrepancie->type;
    }
    $description = "";
    if (isset($discrepancie->description) && !empty($discrepancie->description)) {
        $description = $discrepancie->description;
    }
    $status = "";
    if (isset($discrepancie->status) && !empty($discrepancie->status)) {
        $status = $discrepancie->status;
    }
    $is_additional = "";
    if (isset($discrepancie->is_additional) && !empty($discrepancie->is_additional)) {
        $is_additional = $discrepancie->is_additional;
    }
    $created_at = "";
    if (isset($discrepancie->created_at) && !empty($discrepancie->created_at)) {
        $created_at = date('d, M Y',strtotime($discrepancie->created_at));
    }
    $data = [
        'id' => $id,
        'company' => $companyName->name,
        'company_key' => $companyName->company_key,
        'hasEmployee' => $hasEmployee,
        'hasAttendance' => $hasAttendance,
        'approved_by' => $approved_by,
        'date' => $date,
        'type' => $type,
        'description' => $description,
        'status' => $status,
        'is_additional' => $is_additional,
        'created_at' => $created_at,
        'discrepancie' => $discrepancie,
    ];
    return $data;
}

function getDiscrepancieDetail($id){
    foreach (companies() as $portalName => $portalDb) {
        $discrepancie = '';
        $discrepancie = Discrepancy::on($portalDb)->where('id', $id)->first();
        if (isset($discrepancie) && !blank($discrepancie)) {
            return $discrepancie;
        } else {
            return 'No Record Found...!';
        }
    }
}