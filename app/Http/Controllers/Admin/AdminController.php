<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\WorkShift;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmploymentStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        $user = User::where('email',$request->email)->first();
        if($user->user_for_portal != null){
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

        }
        else {
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
                // ->editColumn('action', function ($model) {
                //     return view('admin.companies.employees.employee-profile', ['employee' => $model])->render();
                // })
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

    public function getSearchDataOnLoad(Request $request)
    {
        $data['departments'] = getDepartments();
        $data['companies'] = Company::get();
        $data['shifts'] = getShifts();
        $data['statuses'] = EmploymentStatus::get();
        return ['success' => true, 'message' => null, 'data' => $data, 'status' => 200];
    }




}
