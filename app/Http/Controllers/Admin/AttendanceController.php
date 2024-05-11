<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\UserLeave;
use App\Models\Attendance;
use App\Models\Discrepancy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\WorkingShiftUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function allCompanies(Request $request)
    {
        $this->authorize('attendances-show-companies');
        $title = 'Select Company';
        $companies = getAllCompanies();
        return view('admin.companies.attendance.companies', compact('companies', 'title'));
    }




    public function companyAttendance(Request $request, $company = null, $getMonth = null, $getYear = null, $user_slug = null)
    {

        $this->authorize('attendances-list');
        $title = 'Attendance Summary';

        $employees = [];

        if(!empty($request->company)){

            $company = $request->company;

        }else{
            $company = "";
        }

        if (isset($request->slug) && !empty($request->slug)) {
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
                    $user  = User::on($portalName)->with('profile', 'employeeStatus', 'userWorkingShift')->where('slug', $request->slug)->first();
                }
            }
        }else{
            $user = [];
        }
        // $employees =  User::where('id', '!=', $user->id)->where('status', 1)->where('is_employee', 1)->select(['id', 'slug', 'first_name', 'last_name', 'email'])->get();
        $employees = getEmployees($company);
        $companies = companies();




        $currentMonth = date('m/Y');
        if (date('d') > 25) {
            $currentMonth = date('m/Y', strtotime('first day of +1 month'));
        }
        if (!empty($request->month) || !empty($request->slug)) {

            $year = $request->year;
            $month = $request->month;
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
        }
        $shift = "";
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

        }
          
           
        $leave_report = "";
        $user_have_used_discrepancies = "";
        $user_joining_date = ""; 
        $leave_types = "";
        $user_leave_report  = "";
        $remaining_filable_leaves = "";
            //User Leave & Discrepancies Reprt
        if(!empty($user)){
            $leave_report = hasExceededLeaveLimit($user, $company);

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
        } 
           

          

    

            // Get the current date
            $currentDate = Carbon::now();

            // Set the current date to the 26th of the previous month
            $startDate = $currentDate->subMonth()->setDay(26)->toDateString();

            // Set the current date to the 25th of the current month
            $endDate = $currentDate->startOfMonth()->addDay(24)->toDateString();
            // $monthDays = getMonthDaysForSalary($getYear ?? null , $getMonth ?? null);
            $monthDays = getMonthDaysForSalary($year ?? null, $month ?? null);


            $begin = new DateTime($year . '-' . ((int) $month - 1) . '-26');
            $beginDate = Carbon::parse($begin);
            $start_date = '';
            if (isset($user) && !empty($user)) {
                if (getUserJoiningDate($user)) {
                    $start_date = getUserJoiningDate($user);
                }
            }

            $end = new DateTime($year . '-' . (int) $month . '-25');

            return view('admin.companies.attendance.index', compact('title', 'user', 'user_joining_date', 'shift', 'month', 'year', 'currentMonth', 'employees', 'remaining_filable_leaves', 'startDate', 'endDate', 'currentDate', 'monthDays', 'company', 'begin', 'end','companies','company'));
        
    }

    public function getCompanyEmployees(Request $request){

        $employees = getEmployees($request->company);
        if(!empty($employees)){
            return ['success' => true, 'data' => $employees['total_employees'] ];

        }else{
            return ['success' => false, 'data' => "" ];
        }

    }

    public static function getAttandanceCount($userID, $start_date, $end_date, $status, $shiftID, $company)
    {
       
        $begin = new DateTime($start_date);
        $end   = new DateTime($end_date);
        $totalDays = 0;
        $workDays = 0;
        $lateIn = 0;
        $lateInDates = [];
        $earlyOut = 0;
        $earlyOutDates = [];
        $halfDay = 0;
        $halfDayDates = [];
        $absent = 0;
        $absent_dates = [];
        $discrepancy_late = 0;
        $discrepancy_early = 0;
        $leave_first_half = 0;
        $leave_last_half = 0;
        $leave_single = 0;
        $check_in_out_time = '';

     foreach(companies() as $index => $portalDb){
        if(isset($company) && $company == $index){

            $user = User::on($portalDb)->where('id', $userID)->first();

        }

     }

    
      
    
        $start_date = '';
        if (getUserJoiningDate($user)) {
            $start_date = getUserJoiningDate($user);
        }
 
        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $shiftID = getUserShift($user, $i->format("Y-m-d"), $company);
            $attendance_adjustment = '';
            $end_time = date("Y-m-d", strtotime($i->format("Y-m-d"))) . ' ' . $shiftID->end_time;

            $shiftEndTime = $shiftID->end_time;
            $shiftEndTime = date('H:i', strtotime($shiftEndTime));
            $carbonEndTime = Carbon::createFromFormat('H:i', $shiftEndTime);

            if ($carbonEndTime->hour < 12) {
                $next = date("Y-m-d", strtotime('+1 day ' . $i->format("Y-m-d")));
            } else {
                $next = date('Y-m-d', strtotime($end_time));
            }
            $beginDate = Carbon::parse($begin);
            $start_time = date('Y-m-d', strtotime($beginDate)) . ' ' . $shiftID->start_time;
            $end_time = date("Y-m-d", strtotime($next)) . ' ' . $shiftID->end_time;
            $shiftStartTime = date("Y-m-d H:i:s", strtotime('-6 hours ' . $start_time));
            $shiftEndTime = date("Y-m-d H:i:s", strtotime('+6 hours ' . $end_time));


            $day = date("D", strtotime($i->format("Y-m-d")));
            $dateArray = [];

            $checkHoliday = checkHoliday($userID, $i->format("Y-m-d"), $company); //check it is holiday or company off

            if (empty($checkHoliday)) {

                if ($day != 'Sat' && $day != 'Sun') {
                    $reponse = getAttandanceSingleRecord($userID, $i->format("Y-m-d"), $next, 'all', $shiftID, $company);
                    if ($reponse != null) {
                        $attendance_date = $reponse['attendance_date'];

                        if (isset($reponse['attendance_id']) && !empty($reponse['attendance_id'])) {
                            $check_att = checkAttendanceByID($reponse['attendance_id'], $company);  // object of Attendance
                            if (!empty($check_att)) {
                                $attendance_date = $check_att;
                            }
                        }

                        $attendance_adjustment = attendanceAdjustment($userID, $reponse['attendance_id'], $i->format("Y-m-d"), $company);

                        if (
                            $reponse['type'] == 'absent' &&
                            $i->format("Y-m-d") < date('Y-m-d') &&
                            empty($attendance_adjustment) ||
                            isset($attendance_adjustment) &&
                            !empty($attendance_adjustment) &&
                            $attendance_adjustment->mark_type == 'absent' &&
                            $i->format("Y-m-d") <= date('Y-m-d')
                        ) {
                            $absent++;

                            $applied_date = $reponse['applied_leaves'];
                            $marked_label = '';
                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $absent--;
                                }
                                $absent_dates[] = [
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'status' => $applied_date->status,
                                    'type' => $applied_date->behavior_type,
                                    'applied_at' => $applied_date->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $type = $reponse['type'];
                                $marked_label = '';
                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as Absent';
                                }
                                $absent_dates[] = [
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        }


                        if (
                            isset($attendance_adjustment) &&
                            !empty($attendance_adjustment->mark_type) &&
                            $attendance_adjustment->mark_type == 'lateIn'
                        ) {
                            $lateIn++;

                            $applied_date = $reponse['applied_discrepancy'];
                            $marked_label = '';
                            $check_in_out_time = '-';
                            if ($if_found = checkAttendanceSummary($userID, $shiftStartTime, $shiftEndTime, $company)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            } elseif ($if_found = Attendance::where('behavior', 'I')->where('user_id', $userID)->whereBetween('in_date', [$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'asc')->first()) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }

                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $lateIn--;
                                }

                                $lateInDates[] = [
                                    'attendance_id' => $applied_date->attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'type' => $applied_date->type,
                                    'status' => $applied_date->status,
                                    'applied_at' => $applied_date->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as Late In';
                                }
                                if (!empty($attendance_date) && !empty($reponse['attendance_id'])) {
                                    $attendance_id = $attendance_date->id;
                                    $behavior = $attendance_date->behavior;
                                } else {
                                    $attendance_id = $i->format("Y-m-d");
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                $lateInDates[] = [
                                    'attendance_id' => $attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'behavior' => $behavior,
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        } elseif (
                            $reponse['type'] == 'lateIn' &&
                            empty($attendance_adjustment)
                        ) {
                            $lateIn++;

                            $applied_date = $reponse['applied_discrepancy'];

                            $marked_label = '';
                            $check_in_out_time = '-';


                            if ($if_found = checkAttendanceSummary($userID, $shiftStartTime, $shiftEndTime, $company)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            } elseif ($if_found = Attendance::where('behavior', 'I')->where('user_id', $userID)->whereBetween('in_date', [$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'asc')->first()) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }

                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $lateIn--;
                                }

                                $lateInDates[] = [
                                    'applied_discrepency' => $reponse['applied_discrepancy'] ?? null,
                                    'attendance_id' => $applied_date->attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'type' => $applied_date->type,
                                    'status' => $applied_date->status,
                                    'applied_at' => $applied_date->created_at,
                                    'label' => $marked_label,

                                ];
                            } else {
                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as ' . $type;
                                }
                                if (!empty($attendance_date) && !empty($reponse['attendance_id'])) {
                                    $attendance_id = $attendance_date->id;
                                    $behavior = $attendance_date->behavior;
                                } else {
                                    $attendance_id = $i->format("Y-m-d");
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                $lateInDates[] = [
                                    'applied_discrepency' => $reponse['applied_discrepancy'] ?? null,
                                    'attendance_id' => $attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'behavior' => $behavior,
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                    'response_type' => $reponse['type'] ?? 0,
                                ];
                            }
                        }




                        if (isset($attendance_adjustment) && !empty($attendance_adjustment->mark_type) && $attendance_adjustment->mark_type == 'earlyout') {
                            $earlyOut++;

                            $applied_date = $reponse['applied_discrepancy'];
                            $check_in_out_time = '';



                            if ($if_found = checkAttendanceSummary($userID, $shiftStartTime, $shiftEndTime, $company)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            } elseif ($if_found = Attendance::where('behavior', 'O')->where('user_id', $userID)->whereBetween('in_date', [$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'desc')->first()) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }
                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $earlyOut--;
                                }

                                $earlyOutDates[] = [
                                    'attendance_id' => $applied_date->attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'type' => $applied_date->type,
                                    'status' => $applied_date->status,
                                    'applied_at' => $applied_date->created_at,
                                ];
                            } else {
                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as ' . $type;
                                }
                                if (!empty($attendance_date) && !empty($reponse['attendance_id'])) {
                                    $attendance_id = $attendance_date->id;
                                    $behavior = $attendance_date->behavior;
                                } else {
                                    $attendance_id = $i->format("Y-m-d");
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                if (!empty($attendance_date)) {
                                    $earlyOutDates[] = [
                                        'attendance_id' => $attendance_id,
                                        'time' => $check_in_out_time,
                                        'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                        'unformatted_date' =>  $i->format("Y-m-d"),
                                        'behavior' => $behavior,
                                        'status' => '',
                                        'type' => $type,
                                    ];
                                }
                            }
                        } elseif ($reponse['type'] == 'earlyout' && empty($attendance_adjustment)) {
                            $earlyOut++;

                            $applied_date = $reponse['applied_discrepancy'];
                            $check_in_out_time = '';


                            if ($if_found = checkAttendanceSummary($userID, $shiftStartTime, $shiftEndTime, $company)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            } elseif ($if_found = Attendance::where('behavior', 'O')->where('user_id', $userID)->whereBetween('in_date', [$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'desc')->first()) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }
                            if (!empty($applied_date)) {
                                if ($applied_date->status == 1) {
                                    $earlyOut--;
                                }

                                $earlyOutDates[] = [
                                    'attendance_id' => $applied_date->attendance_id,
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'type' => $applied_date->type,
                                    'status' => $applied_date->status,
                                    'applied_at' => $applied_date->created_at,
                                ];
                            } else {
                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as ' . $type;
                                }
                                if (!empty($attendance_date) && !empty($reponse['attendance_id'])) {
                                    $attendance_id = $attendance_date->id;
                                    $behavior = $attendance_date->behavior;
                                } else {
                                    $attendance_id = $i->format("Y-m-d");
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                if (!empty($attendance_date)) {
                                    $earlyOutDates[] = [
                                        'attendance_id' => $attendance_id,
                                        'time' => $check_in_out_time,
                                        'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                        'unformatted_date' =>  $i->format("Y-m-d"),
                                        'behavior' => $behavior,
                                        'status' => '',
                                        'type' => $type,
                                    ];
                                }
                            }
                        }

                        if ((isset($attendance_adjustment) && !empty($attendance_adjustment->mark_type) &&  ($attendance_adjustment->mark_type == 'halfday'))) {

                            $halfDay++;

                            $halfDayDate = $reponse['applied_leaves'];
                            $marked_label = '';
                            if (!empty($halfDayDate)) {
                                if ($halfDayDate->status == 1) {
                                    $halfDay--;
                                }
                                $halfDayDates[] = [
                                    'date' => date('d M, Y', strtotime($halfDayDate->start_at)),
                                    'unformatted_date' => $halfDayDate->start_at,
                                    'status' => $halfDayDate->status,
                                    'type' => $halfDayDate->behavior_type,
                                    'applied_at' => $halfDayDate->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $in_date = '';
                                $behavior = '';
                                $time = '';

                                $type = $reponse['type'];

                                if (!empty($attendance_adjustment->mark_type)) {
                                    $type = $attendance_adjustment->mark_type;
                                    $marked_label = ' - Marked as Half Day';
                                }

                                if (!empty($attendance_date) && !empty($reponse['attendance_id'])) {
                                    $in_date = date('d M, Y', strtotime($attendance_date->in_date));
                                    $behavior = $attendance_date->behavior;
                                } else {
                                    $in_date = date('d M, Y', strtotime($attendance_date));
                                    $behavior = $attendance_adjustment->mark_type;
                                }
                                $halfDayDates[] = [
                                    'date' => $in_date,
                                    'time' => '-',
                                    'behavior' => $behavior,
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        } elseif (($reponse['type'] == 'firsthalf' || $reponse['type'] == 'lasthalf') && empty($attendance_adjustment)) {
                            $halfDay++;

                            $halfDayDate = $reponse['applied_leaves'];
                            $marked_label = '';

                            if ($if_found = checkAttendanceSummary($userID, $shiftStartTime, $shiftEndTime, $company)) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            } elseif ($if_found = Attendance::on($company)->where('behavior', 'I')->where('user_id', $userID)->whereBetween('in_date', [$shiftStartTime, $shiftEndTime])->orderBy('in_date', 'asc')->first()) {
                                $check_in_out_time = date('h:i A', strtotime($if_found->in_date));
                            }

                            if (!empty($halfDayDate)) {
                                if ($halfDayDate->status == 1) {
                                    $halfDay--;
                                }
                                $halfDayDates[] = [
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'status' => $halfDayDate->status,
                                    'type' => $halfDayDate->behavior_type,
                                    'applied_at' => $halfDayDate->created_at,
                                    'label' => $marked_label,
                                ];
                            } else {
                                $in_date = '';
                                $behavior = '';
                                $time = '';

                                $type = $reponse['type'];

                                $halfDayDates[] = [
                                    'time' => $check_in_out_time,
                                    'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                    'unformatted_date' =>  $i->format("Y-m-d"),
                                    'behavior' => $type,
                                    'status' => '',
                                    'type' => $type,
                                    'label' => $marked_label,
                                ];
                            }
                        }
                        if ($reponse['punchIn'] != '-') {
                            $workDays++;
                        }
                    }
                    $totalDays++;
                } elseif ($i->format("Y-m-d") <= date('Y-m-d') && isset($user->employeeStatus->employmentStatus) && ($user->employeeStatus->employmentStatus->name == 'Permanent' || $user->employeeStatus->employmentStatus->name == 'Terminated') && $beginDate->greaterThanOrEqualTo($start_date)) {
                    if ($day == 'Sat') {
                        $date = Carbon::createFromFormat('Y-m-d', $i->format("Y-m-d"));
                        $nextDate = $date->copy()->addDays(2);
                        $secondNextDate = $nextDate->copy()->addDay();
                        $previousDate = $date->copy()->subDay();
                    } elseif ($day == 'Sun') {
                        $date = Carbon::createFromFormat('Y-m-d', $i->format("Y-m-d"));
                        $nextDate = $date->copy()->addDay();
                        $secondNextDate = $nextDate->copy()->addDay();

                        $previousDate = $date->copy()->subDays(2);
                    }
                    if ((checkAdjustedAttendance($userID, date('Y-m-d', strtotime($nextDate)), $company) && checkAdjustedAttendance($userID, date('Y-m-d', strtotime($previousDate)), $company)) && checkAttendance($userID, date('Y-m-d', strtotime($nextDate)), date('Y-m-d', strtotime($secondNextDate)), $shiftID, $company) && checkAttendance($userID, date('Y-m-d', strtotime($previousDate)), $i->format("Y-m-d"), $shiftID, $company)) {
                        $absent++;
                        $applied_date = userAppliedLeaveOrDiscrepency($userID, 'absent', date('Y-m-d', strtotime($date)), $company);

                        $marked_label = '';
                        if (!empty($applied_date)) {
                            if ($applied_date->status == 1) {
                                $absent--;
                            }
                            $absent_dates[] = [
                                'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                'unformatted_date' =>  $i->format("Y-m-d"),
                                'status' => $applied_date->status,
                                'type' => $applied_date->behavior_type,
                                'applied_at' => $applied_date->created_at,
                                'label' => $marked_label,
                            ];
                        } else {
                            $type = 'absent';
                            $absent_dates[] = [
                                'unformatted_date' =>  $i->format("Y-m-d"),
                                'date' => date('d M, Y', strtotime($i->format("Y-m-d"))),
                                'status' => '',
                                'type' => $type,
                                'label' => $marked_label,
                            ];
                        }
                    }
                }
            }
        }

        $data = array(
            'totalDays' => $totalDays,
            'workDays' => $workDays,
            'lateIn' => $lateIn,
            'lateInDates' => $lateInDates,
            'earlyOut' => $earlyOut,
            'earlyOutDates' => $earlyOutDates,
            'halfDay' => $halfDay,
            'halfDayDates' => $halfDayDates,
            'absent' => $absent,
            'absent_dates' => $absent_dates,
            'discrepancy_late' => $discrepancy_late,
            'discrepancy_early' => $discrepancy_early,
            'leave_first_half' => $leave_first_half,
            'leave_last_half' => $leave_last_half,
            'leave_single' => $leave_single,
            'date_array' =>  $dateArray ?? null,
        );

        return $data;
    }


    // public function monthlyAttendanceReportExport(Request $request){

      

    //     $response = new StreamedResponse(function () use($request){


    //         $company = $request->company;
    
    //         $slug = $request->slug;
          

    //             // Open output stream
    //             $handle = fopen('php://output', 'w');

                
    //             // Add CSV headers
    //             fputcsv($handle, [
    //                 'S.NO#',
    //                 'MONTH',
    //                 'FROM',
    //                 'TO',
    //                 'EMPLOYEE',
    //                 'WORKING DAYS',
    //                 'REGULAR DAYS',
    //                 'LATE IN',
    //                 'EARLY OUTS',
    //                 'HALF DAYS',
    //                 'ABSENTS',
    //                 'SHIFT',
    //             ]);

    //             // Get all users


    //             foreach(companies() as $index => $portalDb){

    //                 if(isset($company) && $company == $index){

    //                     User::on($portalDb)->where('slug',$slug)->chunk(500, function ($users) use ($handle,$request) {
    //                         foreach ($users as $user) {
                
    //                             $total_days = 0;
    //                             $regulars = 0;
    //                             $late_ins = 0;
    //                             $early_outs = 0;
    //                             $half_days = 0;
    //                             $absents = 0;
    //                             if (!empty($user->userWorkingShift)) {
    //                                 $shift =  $user->userWorkingShift->workShift;
    //                             } else {
    //                                 if (isset($user->departmentBridge->department->departmentWorkShift->workShift) && !empty($user->departmentBridge->department->departmentWorkShift->workShift->id)) {
    //                                     $shift =  $user->departmentBridge->department->departmentWorkShift->workShift;
    //                                 }
    //                             }
    //                             if (!empty($request->month) || !empty($request->slug)) {

    //                                 $year = $request->year;
    //                                 $month = $request->month;
    //                             } else {
    //                                 $year = date('Y');
    //                                 if (date('d') > 26 || (date('d') == 26 && date('H') > 11)) {
    //                                     $month = date('m', strtotime('first day of +1 month'));
    //                                 } else {
    //                                     $month = date('m');
    //                                 }
    //                                 if ($month == 01) {
    //                                     $year = date('Y', strtotime('first day of +1 month'));
    //                                 }
    //                             }
        
    //                             $daysData = getMonthDaysForSalary($year, $month);
                            

    //                             // Add a new row with data
    //                             fputcsv($handle, [
    //                                 'sno' => $user->id,
    //                                 'month' =>  $month,
    //                                 'from' => $daysData->first_date ?? "-",
    //                                 'to' => $daysData->last_date ?? "-",
    //                                 'name' => getUserName($user),
    //                                 'working_days' => $daysData->total_days ?? 0,
    //                                 'regular' => rand(1, 30)  ?? 0,
    //                                 'late_in' => rand(1, 30) ?? 0,
    //                                 'early_out' => rand(1, 30)  ?? 0,
    //                                 'half_day' => rand(1, 30)  ?? 0,
    //                                 'absents' => rand(1, 30) ?? 0,
    //                             ]);
    //                         }
    //                     });


                        


    //                 }
    //                 fclose($handle);

    //             }, 200, [

    //                 'Content-Type' => 'text/csv',
    //                 'Content-Disposition' => 'attachment; filename=' . $reportName,
    //             ]);
               




    //     });




    // }
}
