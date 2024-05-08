<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use App\Models\User;
use App\Models\Company;
use App\Models\LeaveType;
use App\Models\UserLeave;
use App\Models\WorkShift;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Discrepancy;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\EmploymentStatus;
use App\Models\WorkingShiftUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $this->authorize('dashboards-list');
        $data = [];
        $data['title'] = 'Dashboard';
        return view('admin.dashboards.dashboard', compact('data'));
    }

    public function getCounterData($counterBox)
    {
        $data = [];
        $data['box'] = $counterBox;
        if ($counterBox == 'companies-count') {
            $data['counter'] = count(companies());
        } elseif ($counterBox == 'total-employees-count') {
            $data['counter'] = getAllCompaniesVehicles()['totalEmployees'];
        } elseif ($counterBox == 'total-terminated-employees-count') {
            $data['counter'] = getAllTerminatedEmployees()['all_terminated_employees_count'];
        } elseif ($counterBox == 'total-vehicles-count') {
            $data['counter'] = count(getAllCompaniesVehicles()['vehicles']);
        } elseif ($counterBox == 'total-new-hired-count') {
            $data['counter'] = count(getAllCompaniesNewHiring()['all_new_hiring']);
        } elseif ($counterBox == 'total-terminated-of-current-month-count') {
            $data['counter'] = count(getAllTerminatedEmployeesOfCurrentMonth()['all_terminated_employees_of_current_month']);
        }
        return response()->json($data);
    }

    public function getAttendanceCounterData($counterKey, $jsonKey)
    {
        $data = [];
        $data['box_counter'] = $counterKey;
        $data['box_json'] = $jsonKey;
        if ($counterKey == 'regular-employees-count') {
            $data['counter'] = getDailyAttendanceReport()['today_regulars'];
            $data['json'] = json_encode(getDailyAttendanceReport()['monthly_regulars']);
        } elseif ($counterKey == 'late-in-employees-count') {
            $data['counter'] = getDailyAttendanceReport()['today_lateIns'];
            $data['json'] = json_encode(getDailyAttendanceReport()['monthly_lateIns']);
        } elseif ($counterKey == 'half-day-employees-count') {
            $data['counter'] = getDailyAttendanceReport()['today_hafDays'];
            $data['json'] = json_encode(getDailyAttendanceReport()['monthly_hafDays']);
        } elseif ($counterKey == 'absent-employees-count') {
            $data['counter'] = getDailyAttendanceReport()['today_absents'];
            $data['json'] = json_encode(getDailyAttendanceReport()['monthly_absents']);
        }

        return response()->json($data);
    }

    public function getSliderData()
    {
        return (string) view('admin.dashboards.ajax.slider');
    }

    public function loginForm()
    {
        $title = 'Login';
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('admin.auth.login', compact('title'));
        }
    }
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->user_for_portal != null) {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials, $request->has('remember'))) {
                $user = Auth::user();
                if ($user->status == 1) {
                    //Remember me
                    if ($request->has('remember') && !empty($request->remember)) {
                        setcookie("email", $request->email, time() + 3600);
                        setcookie("password", $request->password, time() + 3600);
                    } else {
                        setcookie("email", "");
                        setcookie("password", "");
                    }
                    return response()->json(['success' => true, 'route' => route('dashboard')]);
                } else {
                    Auth::logout(); // Log out the user if they are not active
                    return response()->json(['error' => 'Your account is not active.']);
                }
            } else {
                return response()->json(['error' => 'Invalid credentials']);
            }
        } else {
            return response()->json(['error' => 'Your Are Not Allowed To Login']);
        }
    }

    public function logOut()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('admin.login');
        }
    }

    public function getCompanies(Request $request)
    {
        $data = [];
        $data['title'] = 'All Companies';

        if ($request->ajax() && $request->loaddata == "yes") {
            $models = getAllCompanies();
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('favicon', function ($model) {
                    return view('admin.companies.logo', ['model' => $model])->render();
                })
                ->addColumn('name', function ($model) {
                    return $model->name ?? '-';
                })
                ->addColumn('total_employees', function ($model) {
                    return '<span class="badge bg-label-info me-1">' . count($model->total_employees) . '</span>';
                })
                ->addColumn('total_vehicles', function ($model) {
                    return '<span class="badge bg-label-primary me-1">' . count($model->vehicles) . '</span>';
                })
                ->editColumn('head', function ($model) {
                    return view('admin.companies.head_profile', ['model' => $model])->render();
                })
                ->editColumn('phone_number', function ($model) {
                    return $model->phone_number ?? '-';
                })
                ->editColumn('email', function ($model) {
                    return $model->email ?? '-';
                })
                ->addColumn('action', function ($model) {
                    return view('admin.companies.company-action', ['model' => $model])->render();
                })
                ->rawColumns(['favicon', 'name', 'total_employees', 'total_vehicles', 'head', 'phone_number', 'email', 'action'])
                ->make(true);
        }

        return view('admin.companies.index', compact('data'));
    }
    public function getCompaniesEmployees(Request $request)
    {
        $data = [];
        $data['title'] = 'All Companies Employees';

        if ($request->ajax() && $request->loaddata == "yes") {
            $records = collect(getAllCompaniesEmployees()['total_employees']);
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->role . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    return $model->department;
                })
                ->addColumn('Company', function ($model) {
                    return $model->company;
                })
                ->addColumn('shift', function ($model) {
                    return $model->shift;
                })
                ->addColumn('emp_status', function ($model) {
                    return $model->employment_status;
                })
                ->editColumn('name', function ($model) {
                    return view('admin.companies.employees.employee-profile', ['employee' => $model])->render();
                })
                ->editColumn('action', function ($model) {
                    return view('admin.companies.employees.employee-action', ['employee' => $model])->render();
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('shift')) {
                        $shift = $request->shift;
                        $query->collection = $query->collection->filter(function ($record) use ($shift) {
                            return str_contains(strtolower($record['shift']), strtolower($shift));
                        });
                    }
                    if ($request->has('status')) {
                        $status = $request->status;
                        $query->collection = $query->collection->filter(function ($record) use ($status) {
                            return str_contains(strtolower($record['employment_status']), strtolower($status));
                        });
                    }
                    if ($request->has('department')) {
                        $department = $request->department;
                        $query->collection = $query->collection->filter(function ($record) use ($department) {
                            return str_contains(strtolower($record['department']), strtolower($department));
                        });
                    }
                    if ($request->has('company')) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($record) use ($company) {
                            return str_contains(strtolower($record['company']), strtolower($company));
                        });
                    }
                })
                ->rawColumns(['name', 'role', 'Department', 'shift', 'emp_status', 'action'])
                ->make(true);
        }
        return view('admin.companies.employees.index', compact('data'));
    }
    public function getCompaniesTerminatedEmployees(Request $request)
    {

        $data = [];
        $data['title'] = 'All Companies Terminated Employees';
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getAllTerminatedEmployees()['all_terminated_employees'];
            return DataTables::of($records)
                ->addIndexColumn()
                ->editColumn('name', function ($model) {
                    return view('admin.companies.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->role . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    return $model->department;
                })
                ->addColumn('Company', function ($model) {
                    return $model->company;
                })
                ->addColumn('shift', function ($model) {
                    return $model->shift;
                })
                ->addColumn('emp_status', function ($model) {
                    return $model->employment_status;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('shift') && !empty($request->shift)) {
                        $shift = $request->shift;
                        $query->collection = $query->collection->filter(function ($record) use ($shift) {
                            return str_contains(strtolower($record['shift']), strtolower($shift));
                        });
                    }
                    if ($request->has('status')  && !empty($request->status)) {
                        $status = $request->status;
                        $query->collection = $query->collection->filter(function ($record) use ($status) {
                            return str_contains(strtolower($record['employment_status']), strtolower($status));
                        });
                    }
                    if ($request->has('department')  && !empty($request->department)) {
                        $department = $request->department;
                        $query->collection = $query->collection->filter(function ($record) use ($department) {
                            return str_contains(strtolower($record['department']), strtolower($department));
                        });
                    }
                    if ($request->has('company')  && !empty($request->company)) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($record) use ($company) {
                            return str_contains(strtolower($record['company']), strtolower($company));
                        });
                    }
                })
                ->rawColumns(['name', 'role', 'Department', 'Company', 'shift', 'emp_status'])
                ->make(true);
        }
        return view('admin.companies.employees.terminated_employees', compact('data'));
    }
    public function getCompaniesVehicles(Request $request)
    {
        $data = [];
        $data['title'] = 'All Companies Vehicles';
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getAllCompaniesVehicles()['vehicles'];

            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('vehicleName', function ($model) {
                    return view('admin.companies.vehicles.vehicle-profile', ['model' => $model])->render();
                })
                ->addColumn('name', function ($model) {
                    return view('admin.companies.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('company', function ($model) {
                    return $model->company ?? '-';
                })
                ->filter(function ($query) use ($request) {

                    if ($request->has('department')  && !empty($request->department)) {
                        $department = $request->department;
                        $query->collection = $query->collection->filter(function ($record) use ($department) {
                            return str_contains(strtolower($record['department']), strtolower($department));
                        });
                    }
                    if ($request->has('company')  && !empty($request->company)) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($record) use ($company) {
                            return str_contains(strtolower($record['company']), strtolower($company));
                        });
                    }
                })
                ->rawColumns(['vehicleName', 'name'])
                ->make(true);
        }
        return view('admin.companies.vehicles.index', compact('data'));
    }

    public function getCompanyEmployees(Request $request, $company)
    {

        $data = [];
        $data['title'] = 'Company Employees';
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getCompanyEmployees($company)['total_employees'];
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->role . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    return $model->department;
                })
                ->addColumn('Company', function ($model) {
                    return $model->company;
                })
                ->addColumn('shift', function ($model) {
                    return $model->shift;
                })
                ->addColumn('emp_status', function ($model) {
                    return $model->employment_status;
                })
                ->editColumn('name', function ($model) {
                    return view('admin.companies.employees.employee-profile', ['employee' => $model])->render();
                })
                ->rawColumns(['emp_status', 'name', 'role', 'Department', 'action'])
                ->make(true);
        }
        return view('admin.companies.employees.index', compact('data', 'company'));
    }
    public function getCompanyVehicles(Request $request, $company)
    {

        $data = [];
        $data['title'] = 'Company Vehicles';
        if ($request->ajax() && $request->loaddata == "yes") {

            $records = getCompanyVehicles($company)['vehicles'];
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('vehicleName', function ($model) {
                    return view('admin.companies.vehicles.vehicle-profile', ['model' => $model])->render();
                })
                ->addColumn('name', function ($model) {
                    return view('admin.companies.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('company', function ($model) {
                    return $model->company ?? '-';
                })
                ->rawColumns(['vehicleName', 'name'])
                ->make(true);
        }
        return view('admin.companies.vehicles.index', compact('data', 'company'));
    }
    public function getCompaniesTerminatedEmployeesOfCurrentMonth(Request $request)
    {
        $data = [];
        $data['status'] = 'terminated_employees_of_current_month';
        $data['title'] = 'All Companies Terminated Employees of current month.';
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getAllTerminatedEmployeesOfCurrentMonth()['all_terminated_employees_of_current_month'];
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->role . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    return $model->department;
                })
                ->addColumn('Company', function ($model) {
                    return $model->company;
                })
                ->addColumn('shift', function ($model) {
                    return $model->shift;
                })
                ->addColumn('emp_status', function ($model) {
                    return $model->employment_status;
                })
                ->editColumn('name', function ($model) {
                    return view('admin.companies.employees.employee-profile', ['employee' => $model])->render();
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('shift') && !empty($request->shift)) {
                        $shift = $request->shift;
                        $query->collection = $query->collection->filter(function ($record) use ($shift) {
                            return str_contains(strtolower($record['shift']), strtolower($shift));
                        });
                    }
                    if ($request->has('status')  && !empty($request->status)) {
                        $status = $request->status;

                        $query->collection = $query->collection->filter(function ($record) use ($status) {

                            return str_contains(strtolower($record['employment_status']), strtolower($status));
                        });
                    }
                    if ($request->has('department')  && !empty($request->department)) {
                        $department = $request->department;
                        $query->collection = $query->collection->filter(function ($record) use ($department) {
                            return str_contains(strtolower($record['department']), strtolower($department));
                        });
                    }
                    if ($request->has('company')  && !empty($request->company)) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($record) use ($company) {
                            return str_contains(strtolower($record['company']), strtolower($company));
                        });
                    }
                })
                ->rawColumns(['emp_status', 'name', 'role', 'Department', 'action'])
                ->make(true);
        }
        return view('admin.companies.employees.terminated_employees', compact('data'));
    }

    public function getCompaniesEmployeesNewHiring(Request $request)
    {
        $data = [];
        $data['status'] = 'new hiring employees of current month';
        $data['title'] = 'All Companies New Hiring Employees';
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = collect(getAllCompaniesNewHiring()['all_new_hiring']);
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->role . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    return $model->department;
                })
                ->addColumn('Company', function ($model) {
                    return $model->company;
                })
                ->addColumn('shift', function ($model) {
                    return $model->shift;
                })
                ->addColumn('emp_status', function ($model) {
                    return $model->employment_status;
                })
                ->editColumn('name', function ($model) {
                    return view('admin.companies.employees.employee-profile', ['employee' => $model])->render();
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('shift')) {
                        $shift = $request->shift;
                        $query->collection = $query->collection->filter(function ($record) use ($shift) {
                            return str_contains(strtolower($record['shift']), strtolower($shift));
                        });
                    }
                    if ($request->has('status')) {
                        $status = $request->status;
                        $query->collection = $query->collection->filter(function ($record) use ($status) {
                            return str_contains(strtolower($record['employment_status']), strtolower($status));
                        });
                    }
                    if ($request->has('department')) {
                        $department = $request->department;
                        $query->collection = $query->collection->filter(function ($record) use ($department) {
                            return str_contains(strtolower($record['department']), strtolower($department));
                        });
                    }
                    if ($request->has('company')) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($record) use ($company) {
                            return str_contains(strtolower($record['company']), strtolower($company));
                        });
                    }
                })
                ->rawColumns(['emp_status', 'name', 'role', 'Department', 'action'])
                ->make(true);
        }
        return view('admin.companies.employees.index', compact('data'));
    }


    public function companyAttendanceOld(Request $request, $company)
    {
        $data = [];
        $data['status'] = '';
        $data['title'] = 'Company Attendance';

        $date = $request->date;
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = collect(getCompanyAttendance($company, $date))['attendance_detail'];
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('date', function ($model) {
                    $date = formatDate($model->in_date);
                    return $date;
                })
                ->addColumn('shift_time', function ($model) {
                    return $model->shift;
                })
                ->addColumn('punch_in', function ($model) {

                    return date('H:i:s', strtotime($model->in_date));
                })
                ->addColumn('punch_out', function ($model) {

                    return date('H:i:s', strtotime($model->out_date));
                })
                ->addColumn('status', function ($model) {

                    if ($model->status == 'lateIn') {
                        return 'Late In';
                    } else if ($model->status == 'earlyout') {
                        return 'Early Out';
                    } else if ($model->status == 'regular') {
                        return 'Regular';
                    }
                })
                ->rawColumns(['date', 'shift_time', 'punch_id', 'punch_out', 'status'])
                ->make(true);
        }


        return view('admin.companies.attendance.index', compact('data', 'company'));
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
        $user = User::where('id', $userID)->first();
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
                    $reponse = self::getAttandanceSingleRecord($userID, $i->format("Y-m-d"), $next, 'all', $shiftID, $company);
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




    public static function getAttandanceSingleRecord($userID, $current_date, $next_date, $status, $shift, $company)
    {


        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalName) {

                $user = User::on($portalDb)->where('id', $userID)->first();
            }
        }
        $beginDate = Carbon::parse($current_date);

        $start_date = '';

        if (getUserJoiningDate($user)) {
            $start_date = getUserJoiningDate($user);
        }

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
                if ($company != null && $company == $portalName) {
                    $punchIn = Attendance::on($portalDb)->where('user_id', $userID)->whereBetween('in_date', [$start, $end])->where('behavior', 'I')->orderBy('in_date', 'asc')->first();
                }
            }
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
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
                if ($company != null && $company == $portalName) {
                    $punchIn = Attendance::on($portalDb)->where('user_id', $userID)->whereBetween('in_date',  [$start, $end])->where('behavior', 'I')->orderBy('in_date', 'asc')->first(); // 2023-12-27 00:28:42
                }
            }
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {
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
            'punchIn' => $checkIn,
            'punchOut' => $checkOut,
            'label' => $label,
            'type' => $type,
            'shiftTiming' => $shiftTiming,
            'shiftType' => $shift->type,
            'workingHours' => $workingHours,
            'workingMinutes' => $workingMinutes,
            'discrepancy' => $discrepancy,
            'discrepancyStatus' => $discrepancyStatus,
            'applied_discrepancy' => $applied_discrepancy,
            'leave' => $leave,
            'leaveStatus' => $leaveStatus,
            'applied_leaves' => $applied_leaves,
            'attendance_date' => $attendance_date,
            'attendance_id' => $attendance_id,
            'user' => $user,
            'punch_in_id' => $punchIn->id ?? null,
            'punch_out_id' => $punchOut->id ?? null,
            'punch_in' => $punchIn ?? null,
            'punch_out' => $punchOut ?? null,
            'shift' => $shift,
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


    public function attendanceSummary(Request $request, $company = null, $getMonth = null, $getYear = null, $user_slug = null)
    {


        $title = 'Attendance Summary';
        $logined_user = Auth::user()->load('profile', 'employeeStatus', 'userWorkingShift');

        $employees = [];


        if ($logined_user->hasRole('Department Manager')) {
            $employees = getTeamMembers($logined_user, $company);
        }

        $currentMonth = date('m/Y');
        if (date('d') > 25) {
            $currentMonth = date('m/Y', strtotime('first day of +1 month'));
        }
        if (!empty($request->month) || !empty($request->slug)) {
         
            $year = $request->year;
            $month =$request->month;
            foreach (companies() as $portalName => $portalDb) {
                if ($company != null && $company == $portalName) {

                    $user = User::on($portalDb)->with('profile', 'employeeStatus', 'userWorkingShift')->where('slug', $request->slug)->select(['id', 'slug', 'first_name', 'last_name', 'email'])->first();
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

            $user = $logined_user;
       
        }

        $shift = !empty($user) ? $user->userWorkingShift : null;
   
        if (empty($shift)) {
            $shift = defaultShift();
        } else {
            $shift = $shift->workShift;
        }

        //User Leave & Discrepancies Reprt
        $leave_report = hasExceededLeaveLimit($user, $company);
        if ($leave_report) {
            $leave_in_balance = $leave_report['leaves_in_balance'];
            $remaining_filable_leaves = $leave_report['total_remaining_leaves'];
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
        if (isset($user->joiningDate->joining_date) && !empty($user->joiningDate->joining_date)) {
            $user_joining_date = date('m/Y', strtotime($user->joiningDate->joining_date));
        }
        foreach (companies() as $portalName => $portalDb) {
            if ($company != null && $company == $portalName) {
                $leave_types = LeaveType::on($portalDb)->where('status', 1)->select(['id', 'name'])->get();
            }
        }
        // $monthDays = getMonthDaysForSalary($getYear ?? null , $getMonth ?? null);
        $monthDays = getMonthDaysForSalary($year ?? null, $month ?? null);
        return view('admin.companies.attendance.summary', compact('title', 'user', 'user_joining_date', 'shift', 'month', 'year', 'currentMonth', 'employees', 'leave_types', 'remaining_filable_leaves', 'monthDays', 'company'));
    }





    public function getSearchDataOnLoad(Request $request)
    {

        $data['departments'] = getDepartments();
        $data['companies'] = getAllCompanies();
        $data['shifts'] = getShifts();
        $data['statuses'] = EmploymentStatus::get();
        return ['success' => true, 'message' => null, 'data' => $data, 'status' => 200];
    }
}
