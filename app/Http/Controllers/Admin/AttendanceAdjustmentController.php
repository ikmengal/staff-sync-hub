<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use App\Models\AttendanceAdjustment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Collection;


class AttendanceAdjustmentController extends Controller
{
    public function index(Request $request){
        $this->authorize('attendance-adjustments-list');
        $data = [];
        $data['title'] = 'All Attendance Adjustments';
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getAttendanceAdjustments();
            return DataTables::of($records['attendance_adjustment'])
            ->addIndexColumn()
            ->addColumn('company', function ($model) {
                return $model->company ?? '';
            })
            ->addColumn('employee_id', function ($model) {
                return view('admin.attendance-adjustments.employee-profile', ['model' => $model->hasEmployee])->render();
            })
            ->addColumn('mark_type', function ($model) {
                $label = '';
                switch ($model->mark_type) {
                    case 'absent':
                        $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Absent">Absent</span>';
                        break;
                    case 'firsthalf':
                        $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Half Day Leave">Half Day</span>';
                        break;
                    case 'lateIn':
                        $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Late In">Late In</span>';
                        break;
                    case 'fullday':
                        $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-original-title="Full Day">Full Day</span>';
                        break;
                }
                return $label;
            })
            ->editColumn('attendance_date', function ($model) {
                return $model->hasAttendance ?? '';
            })
            ->addColumn('created_at', function ($model) {
                return $model->created_at ?? '';
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('company')) {
                    $company = $request->company;
                    $query->collection = $query->collection->filter(function ($record) use ($company) {
                        return str_contains(strtolower($record['company']), strtolower($company));
                    });
                }
                if ($request->has('mark_type')) {
                    $type = $request->mark_type;
                    $query->collection = $query->collection->filter(function ($record) use ($type) {
                        return str_contains(strtolower($record['mark_type']), strtolower($type));
                    });
                }
            })
            ->rawColumns(['company', 'employee_id', 'mark_type', 'attendance_date', 'created_at'])
            ->make(true);
        }
        return view('admin.attendance-adjustments.index', compact('data'));
    }

    public function getSearchDataOnLoad(Request $request){
        $data['companies'] = Company::get();
        if(isset($data) && !blank($data)){
            return ['success' => true, 'message' => null, 'data' => $data, 'status' => 200];
        }else{
            return ['success' => false, 'message' => 'No record found', 'data' => null, 'status' => 401];
        }
    }
}
