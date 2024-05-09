<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\LeaveType;
use App\Models\Discrepancy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\WorkingShiftUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function allCompanies(Request $request){
        $this->authorize('attendances-show-companies');
        $title = 'Select Company';
        $companies = getAllCompanies();
        return view('admin.companies.attendance.companies',compact('companies','title'));

    }

    public function companyAttendance(Request $request, $company = null, $getMonth = null, $getYear = null, $user_slug = null)
    {

        $this->authorize('attendances-list');
        $title = 'Attendance Summary';

        $employees = [];

      if(isset($request->slug) && !empty($request->slug)){
   
        $user  = User::with('profile', 'employeeStatus', 'userWorkingShift')->where('slug',$request->slug)->first();

      }else{
        $user = Auth::user()->load('profile', 'employeeStatus', 'userWorkingShift');
      }
        // $employees =  User::where('id', '!=', $user->id)->where('status', 1)->where('is_employee', 1)->select(['id', 'slug', 'first_name', 'last_name', 'email'])->get();
        $employees = getEmployees($company);
      



        $currentMonth = date('m/Y');
        if (date('d') > 25) {
            $currentMonth = date('m/Y', strtotime('first day of +1 month'));
        }
        if (!empty($request->month) || !empty($request->slug)) {
         
            $year = $request->year;
            $month = $request->month;
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
                    $user = User::on($portalDb)->where('slug', $request->slug)->first();
                }
            }
        } else {
            $year = date('Y');
            if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
                $month = date('m', strtotime('first day of +1 month'));
            } else {
                $month = date('m');
            }
            if ($month == 01) {
                $year = date('Y', strtotime('first day of +1 month'));
            }

            $user = getUser();
        }
        if(!empty($user)){
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
                    $shift = WorkingShiftUser::on($portalDb)->where('user_id', $user->id)->where('end_date', NULL)->first();
                }
            }
            if (empty($shift)) {
                $shift = defaultShift();
            } else {
                $shift = $shift->workShift;
            }
    
            //User Leave & Discrepancies Reprt
        
            $leave_report = hasExceededLeaveLimit($user,$company);
    
            if ($leave_report) {
                $leave_in_balance = $leave_report['leaves_in_balance'];
            } else {
                $leave_in_balance = 0;
            }
    
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
                    $user_have_used_discrepancies = Discrepancy::on($portalDb)->where('user_id', $user->id)->where('status', '!=', 2)->whereMonth('date', Carbon::now()->month)
                        ->whereYear('date', Carbon::now()->year)
                        ->count();
                }
            }
    
            $user_joining_date = date('d-m-Y');
    
            if (isset($user->profile) && !empty($user->profile->joining_date)) {
                $user_joining_date = date('m/Y', strtotime($user->profile->joining_date));
            }
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
                    $leave_types = LeaveType::on($portalDb)->where('status', 1)->get(['id', 'name']);
                }
            }
    
            $user_leave_report = hasExceededLeaveLimit($user, $company);
            $remaining_filable_leaves = $user_leave_report['total_remaining_leaves'];
    
            // Get the current date
            $currentDate = Carbon::now();
    
            // Set the current date to the 26th of the previous month
            $startDate = $currentDate->subMonth()->setDay(26)->toDateString();
    
            // Set the current date to the 25th of the current month
            $endDate = $currentDate->startOfMonth()->addDay(24)->toDateString();
            // $monthDays = getMonthDaysForSalary($getYear ?? null , $getMonth ?? null);
            $monthDays = getMonthDaysForSalary($year ?? null, $month ?? null);
            if(isset($request->month) && !empty($request->year) && isset($request->month) && !empty($request->month) && isset($request->slug) && !empty($request->slug)){
                return view('admin.companies.attendance.index', compact('title', 'user', 'user_joining_date', 'shift', 'month', 'year', 'currentMonth', 'employees', 'remaining_filable_leaves', 'startDate', 'endDate', 'currentDate', 'monthDays', 'company'));
            }else{
                return view('admin.companies.attendance.company-attendance',compact('title', 'user', 'user_joining_date','employees','company','month','year','currentDate','currentMonth'));

            }
           

        }else{
            return back()->with('message-user-not-found', 'User Not Found!');
        }
       
    }

   public function attendanceReport(Request $request){

    $title = 'Monthly Attendance Report';
    $behavior = 'all';
    $user = Auth::user();

    $data = [];
    $employees = [];

    foreach(companies() as $index => $company){

        if(!empty($request->company) && $company == $request->company){
        
            $data['employees'] = User::on($index)->where('status', 1)->where('is_employee', 1)->get();
            $data['users'] = User::on($index)->where('status', 1)->where('is_employee', 1)->paginate(10);

        }

    }
  
    $data['companies'] = companies();
    $year = date('Y');
    $month = date('m');
    if (!empty($request->month)) {
        $year = $request->year;
        $month = $request->month;

        // Calculate the start date (26th of the previous month)
        $from_date = Carbon::create($year, $month, 26, 0, 0, 0)->subMonth();

        // Calculate the end date (25th of the current month) year,month, date, hour, minute, second.
        $to_date = Carbon::create($year, $month, 25, 23, 59, 59);
    } else {
        if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
            $from_date = Carbon::now()->startOfMonth()->addDays(25);
            $to_date = Carbon::now()->startOfMonth()->addMonth()->addDays(24);
        } else {
            $from_date = Carbon::now()->subMonth()->startOfMonth()->addDays(25);
            $to_date = Carbon::now()->startOfMonth()->addDays(24);
        }
    }

    $fullMonthName = \Carbon\Carbon::create(null, $month, 1)->format('F');

    $data['from_date'] = date('Y-m-d', strtotime($from_date));
    $data['to_date'] = date('Y-m-d', strtotime($to_date));
    $data['behavior'] = $behavior;

    return view('admin.companies.attendance.attendance-report', compact('title', 'user', 'data', 'year', 'month','fullMonthName'));

   }
}
