<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\UserLeave;
use App\Models\Discrepancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $model;
    protected $moduleName;

    public function __construct()
    {
        $this->model = "\App\Models\Salary";
        $this->moduleName = "Salary Detail";
    }
    public function index(Request $request)
    {


        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function salaryDetails(Request $request)
    {


        $this->authorize("salaries-list");
        $data['title'] = $this->moduleName;
        $data['companies'] = getAllCompanies();
        $data['company'] = $request->company;
        $data['slug'] = $request->slug;
        $advance_availed_leaves = 0;
        $data['currentMonth'] = date('m/Y');
        if (isset($request->slug) && !empty($request->slug)) {

            foreach (companies() as $index => $item) {
                $user = User::on($item)->where('slug', $request->slug)->first();
            }
        }

        $currency_code = !empty($user) ?  getCurrencyCodeForSalary($user) :  "Rs.";
        $user_joining_date = date('m/Y');
        if (isset($user->joiningDate->joining_date) && !empty($user->joiningDate->joining_date)) {
            $user_joining_date = date('m/Y', strtotime($user->joiningDate->joining_date));
        }

        $data['user_joining_date'] = $user_joining_date;

        $employees = [];
        if (isset($request->company)) {
            $employees = getEmployees($request->company);
        }
        // $month = date('m');
        // $year = date('Y');

        $data['month'] = date('m');
        $data['year'] = date('Y');
        $data['user'] = !empty($user) ? $user : [];
        $data['employees'] = $employees;
        $daysData = getMonthDaysForSalary();
        if (date('d') > 25) {
            $data['currentMonth'] = date('m/Y', strtotime('first day of +1 month'));
            $data['month'] = date('m', strtotime('first day of +1 month'));
            if ($data['month'] == 01) {
                $data['year'] = date('Y', strtotime('first day of +1 month'));
            }
        }
        if (!empty($user->employeeStatus->end_date)) {
            if (isset($getMonth) && !empty($getMonth)) {
                $filterMonthYear = $request->year . '/' . $request->month;
                $lastMonthYear = date('Y/m', strtotime($user->employeeStatus->end_date));
                if ($filterMonthYear <= $lastMonthYear || date('d', strtotime($user->employeeStatus->end_date) > 25)) {
                    $data['month'] = $request->month;
                    $data['year'] = $request->year;
                } else {
                    $data['month'] = date('m', strtotime($user->employeeStatus->end_date));
                    $data['year'] = date('Y', strtotime($user->employeeStatus->end_date));

                    //A Employee is last month if he has extra availed leaves will be deducted salary
                    $leave_report = hasExceededLeaveLimit($user, $request->company);
                    $total_used_leaves = $leave_report['total_used_leaves'];
                    $total_leaves_in_account = $leave_report['total_leaves_in_account'];
                    $advance_used_leaves = 0;
                    if ($total_used_leaves > $total_leaves_in_account) {
                        $advance_used_leaves = $total_used_leaves - $total_leaves_in_account;
                    }

                    $advance_availed_leaves = $advance_used_leaves;
                }
            }
        } else {
            if (isset($request->month) && !empty($request->month)) {
                $data['month'] = $request->month;
                $data['year'] = $request->year;
            }
        }

        $daysData = getMonthDaysForSalary($data['year'], $data['month']);
        $total_earning_days = $daysData->total_days;
        if ((isset($user->employeeStatus->start_date) && !empty($user->employeeStatus->start_date))) {
            // $empStartMonthDate = $user->employeeStatus->start_date;
            $empStartMonthDate = getUserJoiningDate($user);
            $empStartMonthDate = Carbon::parse($empStartMonthDate);
            $empEndMonthDate = $user->employeeStatus->end_date;
            $empEndMonthDate = Carbon::parse($empEndMonthDate);
            $startMonthDate = Carbon::parse($daysData->first_date);
            $endMonthDate = Carbon::parse($daysData->last_date);

            $monthYear = $data['month'] . '/' . $data['year'];
            if ($empStartMonthDate->gte($startMonthDate) && $empStartMonthDate->lte($endMonthDate)) {
                if (!empty($empEndMonthDate) && $empEndMonthDate <= $endMonthDate) {
                    $total_earning_days = $empStartMonthDate->diffInDays($empEndMonthDate->addDay());
                } else {
                    $total_earning_days = $empStartMonthDate->diffInDays($endMonthDate->addDay());
                }
            } elseif ($monthYear == $data['currentMonth']) {
                $currentDate = Carbon::now();
                $total_earning_days = $currentDate->diffInDays($startMonthDate);
            } else {
                $salaryMonthYear = Carbon::createFromFormat('m/Y', $monthYear);
                $currentMonthYear = Carbon::createFromFormat('m/Y', $data['currentMonth']);
                if ($salaryMonthYear->greaterThan($currentMonthYear) && date('d', strtotime($user->employeeStatus->start_date) > 25)) {
                    $explode_month_year = explode('/', $data['currentMonth']);
                    $data['month'] = $explode_month_year[0];
                    $data['year'] = $explode_month_year[1];
                    return redirect('employees/salary_details/' . $data['month'] . '/' . $data['year'] . '/' . $data['user']->slug);
                }
            }
        }
        $data['total_earning_days'] = $total_earning_days;
        $date = date('F Y', mktime(0, 0, 0, $data['month'], 1, $data['year']));
        $data['month_year'] = $date;
        $date = Carbon::create($data['year'], $data['month']);
        // Create a Carbon instance for the specified month
        $dateForMonth = Carbon::create($data['year'], $data['month'], 1);

        // Calculate the start date (26th of the specified month)
        $startDate = $dateForMonth->copy()->subMonth()->startOfMonth()->addDays(25);
        $endDate = $dateForMonth->copy()->startOfMonth()->addDays(25);

        // Calculate the total days
        $data['totalDays'] = $startDate->diffInDays($endDate);

        $data['salary'] = 0;
        if (isset($user->salaryHistory) && !empty($user->salaryHistory->salary)) {
            // $data['salary'] =  $user->salaryHistory->salary;
            $data['salary'] =  getUserSalary($user, $data['month'], $data['year'],$request->company);
            $data['per_day_salary'] = $data['salary'] / $data['totalDays'];
        } else {
            $data['per_day_salary'] = 0;
            $data['actual_salary'] =  0;
        }

        if (isset($user->userWorkingShift) && !empty($user->userWorkingShift->working_shift_id)) {
            $data['shift'] = $user->userWorkingShift->workShift;
        } else {
            $data['shift'] = defaultShift();
        }
        $statistics = [];

        if (!empty($user)) {

            if (isset($empStartMonthDate) && ($empStartMonthDate->gte($startMonthDate) && $empStartMonthDate->lte($endMonthDate)) && $empEndMonthDate->lte($endMonthDate)) {

                $statistics = getAttandanceCount($user->id, date('Y-m-d', strtotime($empStartMonthDate)), date('Y-m-d', strtotime($empEndMonthDate) - 1), 'all', $data['shift'], $request->company);
                $lateIn = count($statistics['lateInDates']);
                $earlyOut = count($statistics['earlyOutDates']);
                $total_discrepancies = $lateIn + $earlyOut;
            }
        }

        $filled_discrepencies = "";
        if (!empty($user)) {
            foreach (companies() as $index => $item) {
                if (isset($request->company) && $index == $request->company) {
                    $filled_discrepencies = Discrepancy::on($item)->where('user_id', $user->id)->where('status', 1)->whereBetween('date', [$startDate, $endDate])->count();
                }
            }
        }


        $total_over_discrepancies = 0;
        $discrepancies_absent_days = 0;
        $total_discrepancies = 0;
        $data['late_in_early_out_amount'] = 0;
        if ($filled_discrepencies > 2 && $total_discrepancies > $filled_discrepencies) {
            $total_over_discrepancies = $total_discrepancies - $filled_discrepencies;
            $discrepancies_absent_days = floor($total_over_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        } elseif ($total_over_discrepancies > 2) {
            $discrepancies_absent_days = floor($total_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        } elseif ($filled_discrepencies == 0 && $total_discrepancies > 2) {
            $discrepancies_absent_days = floor($total_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        }



        //Calculation late in and early out days amount.
        $total_approved_discrepancies = 0;

        if ($filled_discrepencies > 2) {
            $total_approved_discrepancies = floor($total_over_discrepancies / 3);
            $total_approved_discrepancies = $total_approved_discrepancies / 2;
        }
        $data['totalDiscrepanciesEarlyOutApprovedAmount'] = $total_approved_discrepancies * $data['per_day_salary'];
        //Calculation late in and early out days amount.
        $filled_full_day_leaves = 0;
        $filled_half_day_leaves = 0;
        if (!empty($user)) {
            foreach (companies() as $index => $item) {
                $filled_full_day_leaves = UserLeave::on($item)->where('user_id', $user->id)
                    ->where('status', 1)
                    ->whereMonth('start_at', $data['month'])
                    ->whereYear('start_at', $data['year'])
                    ->where('behavior_type', 'Full Day')
                    ->get();
            }
            $filled_full_day_leaves = $filled_full_day_leaves->sum('duration');

            foreach (companies() as $index => $item) {
                $filled_half_day_leaves = UserLeave::on($item)->where('user_id', $user->id)
                    ->where('status', 1)
                    ->whereMonth('start_at', $data['month'])
                    ->whereYear('start_at', $data['year'])
                    ->where('behavior_type', 'First Half')
                    ->orWhere('behavior_type', 'Last Half')
                    ->count();
            }
        }


        $over_half_day_leaves = 0;
        $over_absent_days = 0;
        $absent_days_amount = 0;
        if (!empty($statistics)) {
            if ($filled_half_day_leaves > 0) {
                $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;
                $over_half_day_leaves = $filled_half_day_leaves / 2;

                $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
            } else {
                $over_half_day_leaves = $statistics['halfDay'] / 2;
                $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
            }

            if ($filled_full_day_leaves > 0) {
                $over_absent_days = $statistics['absent'] - $filled_full_day_leaves;
                $data['absent_days_amount'] = $over_absent_days * $data['per_day_salary'];
            } else {
                $data['absent_days_amount'] = $statistics['absent'] * $data['per_day_salary'];
                $over_absent_days = $statistics['absent'];
            }
        }


        //Calculate extra availed leaves
        $data['extra_availed_leave_days_amount'] = 0;
        if ($advance_availed_leaves > 0) {
            $data['extra_availed_leave_days_amount'] = $advance_availed_leaves * $data['per_day_salary'];
        }

        //calculation approved absent and half days amount.
        $totalApprovedFullDayHalfDays = $filled_half_day_leaves + $filled_full_day_leaves;
        $totalApprovedFullDayHalfDaysAmount = 0;
        if ($totalApprovedFullDayHalfDays > 0) {
            $totalApprovedFullDayHalfDaysAmount = $totalApprovedFullDayHalfDays * $data['per_day_salary'];
        }
        $data['totalApprovedFullDayHalfDayAmount'] = $totalApprovedFullDayHalfDaysAmount;
        //calculation approved absent and half days amount.

        //total Approved Amount
        $data['totalApprovedAmount'] = $data['totalApprovedFullDayHalfDayAmount'] + $data['totalDiscrepanciesEarlyOutApprovedAmount'];
        //total Approved Amount

        $total_full_and_half_days_absent = $over_absent_days + $over_half_day_leaves;

        $all_absents = $total_full_and_half_days_absent + $discrepancies_absent_days;
        $all_absent_days_amount = $data['per_day_salary'] * $all_absents;
        $logData['all_absents'] = $all_absents;
        $logData['over_half_day_leaves'] = $over_half_day_leaves;
        $data['earning_days_amount'] =  $data['total_earning_days'] * $data['per_day_salary'];

        if (!empty($user->hasAllowance) && date('Y-m-d') >= date('Y-m-d', strtotime($user->hasAllowance->effective_date))) {
            $data['car_allowance'] = $user->hasAllowance->allowance;
        } else {
            $data['car_allowance'] = 0;
        }
        $data['total_actual_salary'] = number_format($data['salary'] + $data['car_allowance']);
        $totalApprovedDaysAndAbsentDaysAmount = $data['totalApprovedAmount'] + $all_absent_days_amount;
        $total_earning_salary = $data['earning_days_amount'] - $totalApprovedDaysAndAbsentDaysAmount;
        $data['total_earning_salary'] = number_format($data['earning_days_amount'] + $data['car_allowance']);
        $data['total_leave_discrepancies_approve_salary'] = $all_absent_days_amount;
        if (isset($data['late_in_early_out_amount']) && isset($data['half_days_amount']) && isset($data['absent_days_amount'])) {
            $all_absent_days_amount = $data['late_in_early_out_amount'] + $data['half_days_amount'] + $data['absent_days_amount'];
            $total_net_salary = $data['earning_days_amount'] - $all_absent_days_amount;
            //Advanced Availed leave amount
            $total_net_salary = $total_net_salary - $data['extra_availed_leave_days_amount'];
            $data['net_salary'] = number_format($total_net_salary + $data['car_allowance']);
        }


        return view('admin.companies.salaries.index', $data);
    }


    public function generateSalarySlip(Request $request)
    {

        $title = "Salary Slip";
        $model = $this->model;
        $company = $request->company;
        $month = $request->month;
        $year = $request->year;
        $slug = $request->slug;
        $data['slug'] = $slug;
        $dateForMonth = Carbon::create($year, $month, 1);
        $startDate = $dateForMonth->copy()->subMonth()->startOfMonth()->addDays(25);
        $endDate = $dateForMonth->copy()->startOfMonth()->addDays(25);
        $data['per_day_salary'] = 0;
        $data['actual_salary'] =  0;
        $advance_availed_leaves = 0;
        $data['salary'] = 0;
        if (!empty($slug)) {
            foreach (companies() as $index => $portalDb) {

                if (!empty($company) && $index == $company) {
                    $user = User::on($portalDb)->with('bankDetails', 'profile', 'hasPreEmployee', 'jobHistory', 'departmentBridge')->where('slug', $slug)->first();
                    $setting = Setting::on($portalDb)->first();
                }
            }
        }


        $daysData = getMonthDaysForSalary($year, $month);
        $data['currentMonth'] = date('m/Y');

        if (date('d') > 25) {
            $data['currentMonth'] = date('m/Y', strtotime('first day of +1 month'));

            $data['month'] = date('m', strtotime('first day of +1 month'));
            if ($data['month'] == 01) {
                $data['year'] = date('Y', strtotime('first day of +1 month'));
            }
        }



        if (!empty($year) && !empty($month)) {
            $data['month'] = $month;
            $data['year'] = $year;
        }

        $daysData = getMonthDaysForSalary($data['year'], $data['month']);

        $total_earning_days = $daysData->total_days;
        if ((isset($user->employeeStatus->start_date) && !empty($user->employeeStatus->start_date))) {
            // $empStartMonthDate = $user->employeeStatus->start_date;
            $empStartMonthDate = getUserJoiningDate($user);
            $empStartMonthDate = Carbon::parse($empStartMonthDate);
            $startMonthDate = Carbon::parse($daysData->first_date);
            $endMonthDate = Carbon::parse($daysData->last_date);
            $monthYear = $data['month'] . '/' . $data['year'];
            if ($empStartMonthDate->gte($startMonthDate) && $empStartMonthDate->lte($endMonthDate)) {
                $total_earning_days = $empStartMonthDate->diffInDays($endMonthDate->addDay());

                //A Employee is last month if he has extra availed leaves will be deducted salary
                $leave_report = hasExceededLeaveLimit($user,$company);
                $total_used_leaves = $leave_report['total_used_leaves'];
                $total_leaves_in_account = $leave_report['total_leaves_in_account'];

                $advance_used_leaves = 0;
                if ($total_used_leaves > $total_leaves_in_account) {
                    $advance_used_leaves = $total_used_leaves - $total_leaves_in_account;
                }

                $advance_availed_leaves = $advance_used_leaves;
            } elseif ($monthYear == $data['currentMonth']) {
                $currentDate = Carbon::now();
                $total_earning_days = $currentDate->diffInDays($startMonthDate);
            } else {
                $salaryMonthYear = Carbon::createFromFormat('m/Y', $monthYear);
                $currentMonthYear = Carbon::createFromFormat('m/Y', $data['currentMonth']);
                if ($salaryMonthYear->greaterThan($currentMonthYear)) {
                    $explode_month_year = explode('/', $data['currentMonth']);
                    $data['month'] = $explode_month_year[0];
                    $data['year'] = $explode_month_year[1];
                    return redirect('employees/generate_salary_slip/' . $data['month'] . '/' . $data['year'] . '/' . $data['user']->slug);
                }
            }
        }

        $data['total_earning_days'] = $total_earning_days;

        $date = Carbon::createFromFormat('Y-m', $data['year'] . '-' . $data['month']);
        $data['month_year'] = $date->format('M Y');

        $date = Carbon::create($data['year'], $data['month']);

        // Create a Carbon instance for the specified month
        $dateForMonth = Carbon::create($data['year'], $data['month'], 1);

        // Calculate the start date (26th of the specified month)
        $startDate = $dateForMonth->copy()->subMonth()->startOfMonth()->addDays(25);
        $endDate = $dateForMonth->copy()->startOfMonth()->addDays(25);

        // Calculate the total days
        $data['totalDays'] = $startDate->diffInDays($endDate);
        if (isset($user->salaryHistory) && !empty($user->salaryHistory->salary)) {
            // $data['salary'] =  $user->salaryHistory->salary;
            $data['salary'] =  getUserSalary($user, $data['month'], $data['year'],$company);
            $data['per_day_salary'] = $data['salary'] / $data['totalDays'];
        } else {
            $data['per_day_salary'] = 0;
            $data['actual_salary'] =  0;
        }

        if (isset($user->userWorkingShift) && !empty($user->userWorkingShift->working_shift_id)) {
            $data['shift'] = $user->userWorkingShift->workShift;
        } else {
            $data['shift'] = defaultShift();
        }

        $statistics = getAttandanceCount($user->id, $year . "-" . ((int)$month - 1) . "-26", $year . "-" . (int)$month . "-25", 'all', $data['shift'],$company);

        $lateIn = count($statistics['lateInDates']);
        $earlyOut = count($statistics['earlyOutDates']);

        $total_discrepancies = $lateIn + $earlyOut;

        foreach (companies() as $index => $portalDb) {
            if (!empty($company) && $index == $company) {
                $filled_discrepencies = Discrepancy::on($portalDb)->where('user_id', $user->id)->where('status', 1)->whereBetween('date', [$startDate, $endDate])->count();
            }
        }
        $total_over_discrepancies = 0;
        $discrepancies_absent_days = 0;
        $data['late_in_early_out_amount'] = 0;
        if ($filled_discrepencies > 2 && $total_discrepancies > $filled_discrepencies) {
            $total_over_discrepancies = $total_discrepancies - $filled_discrepencies;
            $discrepancies_absent_days = floor($total_over_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        } elseif ($total_over_discrepancies > 2) {
            $discrepancies_absent_days = floor($total_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        } elseif ($filled_discrepencies == 0 && $total_discrepancies > 2) {
            $discrepancies_absent_days = floor($total_discrepancies / 3);
            $discrepancies_absent_days = $discrepancies_absent_days / 2;
            $data['late_in_early_out_amount'] = $discrepancies_absent_days * $data['per_day_salary'];
        }

        //Calculation late in and early out days amount.
        $total_approved_discrepancies = 0;

        if ($filled_discrepencies > 2) {
            $total_approved_discrepancies = floor($total_over_discrepancies / 3);
            $total_approved_discrepancies = $total_approved_discrepancies / 2;
        }
        $data['totalDiscrepanciesEarlyOutApprovedAmount'] = $total_approved_discrepancies * $data['per_day_salary'];
        //Calculation late in and early out days amount.
        foreach (companies() as $index => $portalDb) {
            if (!empty($company) && $company == $index) {
                $filled_full_day_leaves = UserLeave::on($portalDb)->where('user_id', $user->id)
                    ->where('status', 1)
                    ->whereMonth('start_at', $data['month'])
                    ->whereYear('start_at', $data['year'])
                    ->where('behavior_type', 'Full Day')
                    ->get();
            }
        }


        $filled_full_day_leaves = $filled_full_day_leaves->sum('duration');

        foreach (companies() as $index => $portalDb) {
            if (!empty($company) && $company == $index) {
                $filled_half_day_leaves = UserLeave::on($portalDb)->where('user_id', $user->id)
                    ->where('status', 1)
                    ->whereMonth('start_at', $data['month'])
                    ->whereYear('start_at', $data['year'])
                    ->where('behavior_type', 'First Half')
                    ->orWhere('behavior_type', 'Last Half')
                    ->count();
            }
        }

        $over_half_day_leaves = 0;
        if ($filled_half_day_leaves > 0) {
            $filled_half_day_leaves = $statistics['halfDay'] - $filled_half_day_leaves;
            $over_half_day_leaves = $filled_half_day_leaves / 2;

            $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
        } else {
            $over_half_day_leaves = $statistics['halfDay'] / 2;
            $data['half_days_amount'] = $over_half_day_leaves * $data['per_day_salary'];
        }

        $over_absent_days = 0;
        if ($filled_full_day_leaves > 0) {
            $over_absent_days = $statistics['absent'] - $filled_full_day_leaves;
            $data['absent_days_amount'] = $over_absent_days * $data['per_day_salary'];
        } else {
            $data['absent_days_amount'] = $statistics['absent'] * $data['per_day_salary'];
        }

        //Calculate extra availed leaves
        $data['extra_availed_leave_days_amount'] = 0;
        if ($advance_availed_leaves > 0) {
            $data['extra_availed_leave_days_amount'] = $advance_availed_leaves * $data['per_day_salary'];
        }

        //calculation approved absent and half days amount.
        $totalApprovedFullDayHalfDays = $filled_half_day_leaves + $filled_full_day_leaves;
        $totalApprovedFullDayHalfDaysAmount = 0;
        if ($totalApprovedFullDayHalfDays > 0) {
            $totalApprovedFullDayHalfDaysAmount = $totalApprovedFullDayHalfDays * $data['per_day_salary'];
        }
        $data['totalApprovedFullDayHalfDayAmount'] = $totalApprovedFullDayHalfDaysAmount;
        //calculation approved absent and half days amount.

        //total Approved Amount
        $data['totalApprovedAmount'] = $data['totalApprovedFullDayHalfDayAmount'] + $data['totalDiscrepanciesEarlyOutApprovedAmount'];
        //total Approved Amount

        $total_full_and_half_days_absent = $over_absent_days + $over_half_day_leaves;

        $all_absents = $total_full_and_half_days_absent + $discrepancies_absent_days;
        $all_absent_days_amount = $data['per_day_salary'] * $all_absents;
        $logData['all_absents'] = $all_absents;
        $logData['over_half_day_leaves'] = $over_half_day_leaves;
        $data['earning_days_amount'] =  $data['total_earning_days'] * $data['per_day_salary'];

        if (!empty($user->hasAllowance) && date('Y-m-d') >= date('Y-m-d', strtotime($user->hasAllowance->effective_date))) {
            $data['car_allowance'] = $user->hasAllowance->allowance;
        } else {
            $data['car_allowance'] = 0;
        }
        $data['total_actual_salary'] = number_format($data['salary'] + $data['car_allowance']);
        $totalApprovedDaysAndAbsentDaysAmount = $data['totalApprovedAmount'] + $all_absent_days_amount;
        $total_earning_salary = $data['earning_days_amount'] - $totalApprovedDaysAndAbsentDaysAmount;
        $data['total_earning_salary'] = number_format($data['earning_days_amount'] + $data['car_allowance']);
        $data['total_leave_discrepancies_approve_salary'] = $all_absent_days_amount;
        $all_absent_days_amount = $data['late_in_early_out_amount'] + $data['half_days_amount'] + $data['absent_days_amount'];
        $total_net_salary = $data['earning_days_amount'] - $all_absent_days_amount;
        //Advanced Availed leave amount
        $total_net_salary = $total_net_salary - $data['extra_availed_leave_days_amount'];
        $data['net_salary'] = number_format($total_net_salary + $data['car_allowance']);
        $currency_code = !empty($user) ?  getCurrencyCodeForSalary($user) :  "PKR";
        $data['user'] = $user;
        $data['setting'] = $setting;
        return view('admin.companies.salaries.salary-slip', $data);
    }
}
