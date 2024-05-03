<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\User;
use App\Models\WorkShift;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\DepartmentUser;
use App\Models\EmploymentStatus;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $this->authorize('roles-list');
        $data['per_page_records'] = 10;
        $data['title'] = 'All Roles';
        $data['models'] = Permission::groupBy('label')->select('label')->get();
        $data['work_shifts'] = WorkShift::orderby('id', 'desc')->get();
        $data['designations'] = Designation::orderby('id', 'desc')->where('status', 1)->get();
        $data['roles'] = Role::get();
        $data['departments'] = Department::orderby('id', 'desc')->where('status', 1)->get();
        $data['employment_statues'] = EmploymentStatus::orderby('id', 'desc')->get();
        $data['employees_users'] = User::orderby('id', 'desc')->where('is_employee', 1)->take(5)->get();
        $emp_statuses = ['Terminated', 'Voluntary', 'Layoffs', 'Retirements'];
        $data['termination_employment_statues'] = EmploymentStatus::whereIn('name', $emp_statuses)->get();

        $model = User::orderby('id', 'desc')->where('is_employee', 1)->get();
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return $model->getRoleNames()->first();
                })
                ->addColumn('Department', function ($model) {
                    if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                        return $model->departmentBridge->department->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return $model->userWorkingShift->workShift->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '-';

                    if (isset($model->employeeStatus->employmentStatus) && !empty($model->employeeStatus->employmentStatus->name)) {
                        if ($model->employeeStatus->employmentStatus->name == 'Terminated') {
                            $label = '<span class="badge bg-label-danger me-1">Terminated</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Permanent') {
                            $label = '<span class="badge bg-label-success me-1">Permanent</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Probation') {
                            $label = '<span class="badge bg-label-warning me-1">Probation</span>';
                        }
                    }

                    return $label;
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.employees.employee-action', ['employee' => $model])->render();
                })
                ->rawColumns(['first_name', 'action', 'emp_status'])
                ->make(true);
        }

        return view('admin.roles.index', $data);
    }

    public function getRoleEmployees(Request $request)
    {
        $records = User::where('is_employee', 1)->select("*");
        if ($request->ajax()) {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('role', function ($model) {
                    return '<span class="badge bg-label-primary">' . $model->getRoleNames()->first() . '</span>';
                })
                ->addColumn('Department', function ($model) {
                    if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department)) {
                        return '<span class="text-primary">' . $model->departmentBridge->department->name . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('shift', function ($model) {
                    if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name)) {
                        return $model->userWorkingShift->workShift->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('emp_status', function ($model) {
                    $label = '-';

                    if (isset($model->employeeStatus->employmentStatus) && !empty($model->employeeStatus->employmentStatus->name)) {
                        if ($model->employeeStatus->employmentStatus->name == 'Terminated') {
                            $label = '<span class="badge bg-label-danger me-1">Terminated</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Permanent') {
                            $label = '<span class="badge bg-label-success me-1">Permanent</span>';
                        } elseif ($model->employeeStatus->employmentStatus->name == 'Probation') {
                            $label = '<span class="badge bg-label-warning me-1">Probation</span>';
                        } else {
                            $label = '<span class="badge bg-label-info me-1">' . $model->employeeStatus->employmentStatus->name . '</span>';
                        }
                    }

                    return $label;
                })
                ->editColumn('status', function ($model) {
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->editColumn('first_name', function ($model) {
                    return view('admin.employees.employee-profile', ['employee' => $model])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.employees.employee-action', ['employee' => $model])->render();
                })
                ->rawColumns(['emp_status', 'status', 'first_name', 'role', 'Department', 'action'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('roles-create');
        $rules = [
            'name' => 'required|unique:roles',
            'permissions.*' => 'required'
        ];

        $message = [
            'name.unique' => 'This Role name has already been taken.'
        ];


        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(), 'validation' => false]);
        }

        $role = Role::create([
            'name' => $request->name ?? null,
            'display_name' => $request->name ?? null,
            'guard_name' => 'web',
        ]);
        if (isset($request->permissions) && !empty($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

        if (isset($role) && !empty($role)) {


            $result = ['success' => true, 'message' => 'Role successfuly created', 'status' => 200];
        } else {
            $result = ['success' => false, 'message' => 'Role not created, something went wrong', 'status' => 501];
        }

        return $result;
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $this->authorize('roles-edit');
        $role = Role::where('id', $id)->first();
        $role_permissions = $role->permissions->pluck('name')->toArray();
        $models = Permission::orderby('id', 'DESC')->groupBy('label')->get();
        $permissions = Permission::groupBy('label')
        ->select('label')
        ->get();
        $roles = Role::orderby('id', 'desc')->get();
        $view = view('admin.roles.edit_content', compact('role', 'models', 'roles', 'role_permissions','permissions'))->render();
        return ['success' => true, 'view' => $view];
    }



    /**
     * Update the specified resource in storage.
     */



    public function update(Request $request, $id)
    {
        $this->authorize('roles-edit');
        $update = Role::where('id', $id)->first();
        $update->name = $request->name;
        $update->save();
        $update->syncPermissions($request->permissions);

        if (isset($update) && !empty($update)) {

            $result = ['success' => true, 'message' => 'Role successfuly updated', 'status' => 200];
        } else {
            $result = ['success' => false, 'message' => 'Role not updated, something went wrong', 'status' => 501];
        }

        return $result;
    }



    public function showAllUsers(Request $request)
    {
        $this->authorize('roles-all-user');
        $record = Role::where('id', $request->id)->first();
        $users = $record->users ?? null;
        $title = "User List";
        $view = view('admin.roles.show-all-users-modal', compact('users', 'title'))->render();
        return ['success' => true, 'view' => $view];
    }
}
