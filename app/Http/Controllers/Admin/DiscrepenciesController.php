<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use App\Models\AttendanceAdjustment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Discrepancy;


class DiscrepenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('discrepencies-list');
        $data = [];
        $data['title'] = 'All Descrepencies';
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getDiscrepancies();
            return DataTables::of($records['discrepancies'])
                ->addIndexColumn()
                ->addColumn('company', function ($model) {
                    return $model->company ?? '';
                })
                ->addColumn('employee_id', function ($model) {
                    return view('admin.discrepencies.employee-profile', ['model' => $model->hasEmployee])->render();
                })
                ->editColumn('attendance_date', function ($model) {
                    return $model->hasAttendance ?? '';
                })
                ->addColumn('type', function ($model) {
                    $label = '';
                    switch ($model->type) {
                        case 'late':
                            $label = '<span class="badge bg-label-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Late">Late</span>';
                            break;
                        case 'early':
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Early">Early</span>';
                            break;
                    }
                    return $label;
                })
                ->addColumn('is_additional', function ($model) {
                    $label = '-';
                    return isset($model->is_additional) && $model->is_additional == 1 ? '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Additional">Additional</span>' : '-' ;
                })
                ->addColumn('status', function ($model) {
                    $label = '';
                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Approved">Approved</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Rejected">Rejected</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Pending">Pending</span>';
                            break;
                    }
                    return $label;
                })
                ->addColumn('created_at', function ($model) {
                    return $model->created_at ?? '';
                })
                ->addColumn('action', function ($model) {
                    return view('admin.discrepencies.action', ['model' => $model])->render();
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('company')) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($record) use ($company) {
                            return str_contains(strtolower($record['company']), strtolower($company));
                        });
                    }
                    if ($request->has('mark_type')) {
                        $mark_type = $request->mark_type;
                        $query->collection = $query->collection->filter(function ($record) use ($mark_type) {
                            return str_contains(strtolower($record['type']), strtolower($mark_type));
                        });
                    }
                })
                ->rawColumns(['company', 'employee_id', 'attendance_date', 'type', 'is_additional', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('admin.discrepencies.index', compact('data'));
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
    public function show(Request $request, string $id)
    {
        $company = $request->company ?? '';
        $model = getDiscrepancieDetail($id);
        if (isset($model) && !empty($model)) {
            if (view()->exists('admin.discrepencies.show-content')) {
                return view('admin.discrepencies.show-content', compact('model', 'company'))->render();
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
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

    public function getSearchDataOnLoad(Request $request){
        $data['companies'] = Company::get();
        if(isset($data) && !blank($data)){
            return ['success' => true, 'message' => null, 'data' => $data, 'status' => 200];
        }else{
            return ['success' => false, 'message' => 'No record found', 'data' => null, 'status' => 401];
        }
    }
}
