<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\WorkShift;
use App\Models\VehicleUser;
use App\Models\AttendanceSummary;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

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
    $setting = Setting::first();
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

function loginUser(){
    if(Auth::check()){
        $profile = null;
        if(isset(Auth::user()->profile) && !empty(Auth::user()->profile->profile)){
            $profile = Auth::user()->profile->profile;
        }
        $user = (object)[
            'id' => Auth::user()->id,
            'slug' => Auth::user()->slug,
            'name' => Auth::user()->first_name.' '.Auth::user()->last_name,
            'email' => Auth::user()->email,
            'profile' => $profile,
            'role' => Auth::user()->getRoleNames()->first(),
        ];

        return $user;
    }else{
        return null;
    }
}

function getUserData($user){
    $profile = null;
    if(isset($user->profile) && !empty($user->profile->profile)){
        $profile = $user->profile->profile;
    }
    $designation = '-';
    if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title)){
        $designation = $user->jobHistory->designation->title;
    }
    $user = (object)[
        'id' => $user->id,
        'slug' => $user->slug,
        'name' => $user->first_name.' '.$user->last_name,
        'email' => $user->email,
        'profile' => $profile,
        'role' => $user->getRoleNames()->first(),
        'designation' => $designation,
    ];
    return $user;
}

function companies(){ 
    $companies = [
        'cyberonix' => env('CYBERONIX_DB_DATABASE'),
        'vertical' => env('VERTICAL_DB_DATABASE'),
        'braincell' => env('BRAINCELL_DB_DATABASE'),
        'clevel' => env('CLEVEL_DB_DATABASE'),
        'delve' => env('DELVE12_DB_DATABASE'),
        'horizontal' => env('HORIZONTAL_DB_DATABASE'),
        'mercury' => env('MERCURY_DB_DATABASE'),
        'momyom' => env('MOMYOM_DB_DATABASE'),
        'softnova' => env('SOFTNOVA_DB_DATABASE'),
        'softfellow' => env('SOFTFELLOW_DB_DATABASE'),
        'swyftcube' => env('SWYFTCUBE_DB_DATABASE'),
        // 'swyftzone' => env('SWYFTZONE_DB_DATABASE'), // currently not in used
        'techcomrade' => env('TECHCOMRADE_DB_DATABASE'),
        'rocketflare' => env('ROCKETFLARELABS_DB_DATABASE'),
    ];
   
    return $companies;
}
function getAllCompanies(){
    $companies = [];
    
    // Get the current month and year
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;  
    foreach(companies() as $portalName=>$portalDb){  
        $settings = Setting::on($portalDb)->select(['id', 'base_url', 'name', 'phone_number', 'email', 'favicon'])->first(); 
        if(!empty($settings)){
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

            $total_terminated_employees = User::on($portalDb)->where('is_employee', 0)->get();
            $terminatedUsersOfCurrentMonth = User::on($portalDb)->whereHas('hasResignation', function($query) use ($currentMonth, $currentYear) {
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
            if(!empty($head)){
                $company_head = getUserData($head);
            }

            $settings['vehicles'] = $vehicleUsers;
            $settings['vehicle_percent'] = number_format(count($vehicleUsers)/count($total_employees)*100);
            $settings['portalDb'] = $portalDb;
            $settings['total_employees'] = $total_employees;
            $settings['total_new_hiring'] = $total_new_hiring;
            $settings['total_terminated_employees'] = $total_terminated_employees;
            $settings['total_terminated_of_current_month'] = $terminatedUsersOfCurrentMonth;
            $settings['head'] = $company_head;
            $settings['base_url'] = $settings->base_url;
            $settings['company_key'] = $portalName;
            $companies[$portalName] = $settings;
        }else{
            dd("Failed to Load Settings");
        }
    }

    return $companies;
}

//Get Companies & Employees
function getAllCompaniesEmployees(){
    return getEmployees();
}
function getCompanyEmployees($companyName = null){
    return getEmployees($companyName);
}

function getEmployees($companyName=null){
    $data = [];
    $allEmployees = [];
    $total_employees_count = 0;
    foreach(getAllCompanies() as $company){
        if($companyName != null && $companyName==$company->company_key){
            $total_employees_count += count($company->total_employees);
            foreach($company->total_employees as $employee){
                $allEmployees[] = (object) employeeDetails($company, $employee);
            }
            break;
        }elseif($companyName==NULL){
            $total_employees_count += count($company->total_employees);
            foreach($company->total_employees as $employee){
                $allEmployees[] = (object) employeeDetails($company, $employee);
            }
        }
    }

    $data['total_employees_count'] = $total_employees_count;
    $data['total_employees'] =  $allEmployees;

    return $data;
}
//Get Companies & Employees

//Get Vehicles & Employees
function getAllCompaniesVehicles(){
    return getVehicles();
}

function getCompanyVehicles($companyName = null){
    return getVehicles($companyName);
}

function getVehicles($companyName=null){
    $data = [];
    $allCompaniesVehicles = [];

    foreach(getAllCompanies() as $portalName=>$portalDb){
        if($companyName != null && $companyName==$portalDb->company_key){
            $vehicleUsers = VehicleUser::on($portalDb->portalDb)
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
            $setting['vehicles'] = $vehicleUsers;
            $setting['total_employees'] = count($portalDb->total_employees);
            $setting['base_url'] = $portalDb->base_url;
            $setting['company'] = $portalDb->name;
            $allCompaniesVehicles[$portalName] = $setting;

            break;
        }elseif($companyName==null){
            $vehicleUsers = VehicleUser::on($portalDb->portalDb)
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
            $setting['vehicles'] = $vehicleUsers;
            $setting['total_employees'] = count($portalDb->total_employees);
            $setting['base_url'] = $portalDb->base_url;
            $setting['company'] = $portalDb->name;
            $allCompaniesVehicles[$portalName] = $setting;
        }
    }
    $vehicles = [];
    $totalEmployees = 0;
    foreach($allCompaniesVehicles as $companyVehicles){
        $totalEmployees += $companyVehicles['total_employees'];
        foreach($companyVehicles['vehicles'] as $companyVehicle){
            $profile = '';
            $employment_id = '-';
            if(isset($companyVehicle->hasUser->profile) && !empty($companyVehicle->hasUser->profile->profile)){
                $profile = $companyVehicle->hasUser->profile->profile;
                $employment_id = $companyVehicle->hasUser->profile->employment_id;
            }
            $designation = '-';
            if (isset($companyVehicle->hasUser->jobHistory->designation->title) && !empty($companyVehicle->hasUser->jobHistory->designation->title)){
                $designation = $companyVehicle->hasUser->jobHistory->designation->title;
            }
            $vehicleName = '';
            $vehicleThumbnail = '';
            $vehicleModelYear = '';
            $vehicleCc = '';
            if(isset($companyVehicle->hasVehicle) && !empty($companyVehicle->hasVehicle->name)){
                $vehicleName = $companyVehicle->hasVehicle->name;
                $vehicleThumbnail = $companyVehicle->hasVehicle->thumbnail;
                $vehicleCc = $companyVehicle->hasVehicle->engine_capacity;
                $vehicleModelYear = $companyVehicle->hasVehicle->model_year;
            }

            $vehicles[] = (object)[
                'base_url' => $companyVehicles['base_url'],
                'company' => $companyVehicles['company'],
                'avatar_path' => '/public/admin/assets/img/avatars/',
                'profile' => $profile,
                'employment_id' => $employment_id,
                'name' => $companyVehicle->hasUser->first_name.' '.$companyVehicle->hasUser->last_name,
                'designation' => $designation,
                'vehicleName' => $vehicleName,
                'vehicleThumbnail' => $vehicleThumbnail,
                'vehicleModelYear' => $vehicleModelYear,
                'vehicleCc' => $vehicleCc,
            ];
        }
    }
    $data['vehicles'] = $vehicles;
    $data['totalEmployees'] = $totalEmployees;
    return $data;
}
//Get Vehicles & Employees

function getDailyAttendanceReport(){
    if(date('d') > 25){
        $begin = new DateTime(date('Y') . "-" . ((int)date('m')) . "-26");
        $end = new DateTime(date('Y') . "-" . (int)date('m') . '-' . date('d'));
    }else{
        $begin = new DateTime(date('Y') . "-" . ((int)date('m')) - 1 . "-26");
        $end = new DateTime(date('Y') . "-" . (int)date('m') . '-' . date('d'));
    }

    $attendanceDailyData = [];
    $total_regular_employees = [];
    $total_late_in_employees = [];
    $total_half_days_employees = [];
    $total_absent_employees = [];

    for ($i = $begin; $i <= $end; $i->modify('+1 day')){
        $day = date("D", strtotime($i->format("Y-m-d")));

        if ($day != 'Sat' && $day != 'Sun'){
            $next = date("Y-m-d", strtotime('+1 day ' . $i->format("Y-m-d")));
            $totalRegulars = 0;
            $totalLateIns = 0;
            $totalHalfDays = 0;
            $totalAbsents = 0;
            foreach(getAllCompanies() as $company){
                $employee_ids = $company['total_employees']->pluck('id')->toArray();
                foreach(getEmployeesAttendanceCount($company['portalDb'], $employee_ids, $i->format("Y-m-d"), $next) as $key=>$value){
                    if($key=='total_regular'){
                        $totalRegulars += $value;
                    }elseif($key=='total_late_in'){
                        $totalLateIns += $value;
                    }elseif($key=='total_half_days'){
                        $totalHalfDays += $value;
                    }else{
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
        }elseif($attendanceSummary->attendance_type === 'regular'){
            $regularCount++;
        }
    }

    $data['total_regular'] = $regularCount;
    $data['total_late_in'] = $lateInCount;
    $data['total_half_days'] = $halfDayCount;
    $data['total_absent'] = count($employees) - count($attendanceSummaries);

    return $data;
}
function getAllTerminatedEmployees(){
    $data = [];
    $terminated_employees = 0;
    $all_terminated_employees = [];
    foreach(getAllCompanies() as $company){
        $terminated_employees += count($company->total_terminated_employees);
        foreach($company->total_terminated_employees as $employee){
            $all_terminated_employees[] = (object) employeeDetails($company, $employee);
        }
    }

    $data['all_terminated_employees_count'] = $terminated_employees;
    $data['all_terminated_employees'] = $all_terminated_employees;

    return $data;
}

function getAllTerminatedEmployeesOfCurrentMonth(){
    $data = [];
    $terminated_employees = 0;
    $all_terminated_employees = [];
    foreach(getAllCompanies() as $company){
        $terminated_employees += count($company->total_terminated_of_current_month);
        foreach($company->total_terminated_of_current_month as $employee){
            $all_terminated_employees[] = (object) employeeDetails($company, $employee);
        }
    }

    $data['all_terminated_employees_of_current_month'] = $all_terminated_employees;

    return $data;
}

function getAllCompaniesNewHiring(){
    $data = [];
    $new_hired_employees = 0;
    $all_new_hired_employees = [];
    foreach(getAllCompanies() as $company){
        $new_hired_employees += count($company->total_new_hiring);
        foreach($company->total_new_hiring as $employee){
            $all_new_hired_employees[] = (object) employeeDetails($company, $employee);
        }
    }

    $data['all_new_hiring'] = $all_new_hired_employees;

    return $data;
}

function employeeDetails($company, $employee){
    $profile = '';
    $employment_id = '-';
    if(!empty($employee->profile->profile)){
        $profile = $employee->profile->profile;
        $employment_id = $employee->profile->employment_id;
    }
    $designation = '-';
    if (isset($employee->jobHistory->designation->title) && !empty($employee->jobHistory->designation->title)){
        $designation = $employee->jobHistory->designation->title;
    }
    $department = '-';
    if (isset($employee->departmentBridge->department) && !empty($employee->departmentBridge->department)) {
        $department = '<span class="text-primary">' . $employee->departmentBridge->department->name . '</span>';
    }
    $shift = '-';
    if (isset($employee->userWorkingShift->workShift) && !empty($employee->userWorkingShift->workShift->name)) {
        $shift = $employee->userWorkingShift->workShift->name;
    }
    $employment_status = '-';
    if (isset($employee->employeeStatusEndDateNull->employmentStatus) && !empty($employee->employeeStatusEndDateNull->employmentStatus->name)) {
        if ($employee->employeeStatusEndDateNull->employmentStatus->name == 'Terminated') {
            $employment_status = '<span class="badge bg-label-danger me-1">Terminated</span>';
        } elseif ($employee->employeeStatusEndDateNull->employmentStatus->name == 'Permanent') {
            $employment_status = '<span class="badge bg-label-success me-1">Permanent</span>';
        } elseif ($employee->employeeStatusEndDateNull->employmentStatus->name == 'Probation') {
            $employment_status = '<span class="badge bg-label-warning me-1">Probation</span>';
        } else {
            $employment_status = '<span class="badge bg-label-info me-1">' . $employee->employeeStatusEndDateNull->employmentStatus->name . '</span>';
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
        'name' => $employee->first_name.' '.$employee->last_name,
        'email' => $employee->email,
        'role' => $employee->getRoleNames()->first(),
        'designation' => $designation,
        'department' => $department,
        'shift' => $shift,
        'employment_status' => $employment_status,
    ];

    return $data;
}

function todayPresentEmployees(){
    $today_regulars = getDailyAttendanceReport()['today_regulars'];
    $today_lateIns = getDailyAttendanceReport()['today_lateIns'];
    return $today_regulars+$today_lateIns;
}

function getCurrentWeekAttendance(){
    $begin = new DateTime(date('Y') . "-" . ((int)date('m')) . "-05");
    $end = new DateTime(date('Y') . "-" . (int)date('m') . '-' . "09");

    $total_regular_employees = [];
    $total_late_in_employees = [];

    for ($i = $begin; $i <= $end; $i->modify('+1 day')){
        $day = date("D", strtotime($i->format("Y-m-d")));

        if ($day != 'Sat' && $day != 'Sun'){
            $next = date("Y-m-d", strtotime('+1 day ' . $i->format("Y-m-d")));
            $totalRegulars = 0;
            $totalLateIns = 0;
            foreach(getAllCompanies() as $company){
                $employee_ids = $company['total_employees']->pluck('id')->toArray();
                foreach(getEmployeesAttendanceCount($company['portalDb'], $employee_ids, $i->format("Y-m-d"), $next) as $key=>$value){
                    if($key=='total_regular'){
                        $totalRegulars += $value;
                    }elseif($key=='total_late_in'){
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