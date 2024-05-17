<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLeave;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserLeaveController extends Controller
{
    public function index(Request $request){
        $data = [];
        $data['title'] = 'All Companies Leaves';

        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getLeaves();
            return DataTables::of($records['leaves'])
                ->addIndexColumn()
                ->addColumn('company', function ($model) {
                    return $model->company ?? '';
                })
                ->editColumn('user', function ($model) {
                    return $model->user->first_name.' ' .$model->user->last_name ?? '';
                })
                ->addColumn('start_at', function ($model) {
                    // return $model->start_at ?? '';
                    if($model->duration <= 1){
                        return '<span class="fw-semibold"><b>'.Carbon::parse($model->start_at)->format('d-M-Y') . '</b>';
                    }else{
                        return '<span class="fw-semibold"><b>'.Carbon::parse($model->start_at)->format('d-M-Y') . '</b> to <b>' . Carbon::parse($model->end_at)->format('d-M-Y').'</b></span>';
                    }
                })
                ->addColumn('duration', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->duration.'</span>';
                })
                ->addColumn('behavior_type', function ($model) {
                    if($model->behavior_type=='firsthalf'){
                        return '<span class="badge bg-label-info">First Half</span>';
                    }elseif($model->behavior_type=='lasthalf'){
                        return '<span class="badge bg-label-warning">Last Half</span>';
                    }else{
                        if($model->behavior_type=='absent'){
                            return '<span class="badge bg-label-danger">Absent</span>';
                        }else{
                            return '<span class="badge bg-label-primary">'.$model->behavior_type.'</span>';
                        }
                    }
                })
                ->addColumn('status', function ($model) {
                    $label = '';
                    switch ($model->status) {
                        case 'Approved':
                            $label = '<span class="badge bg-label-success" text-capitalized="">Approved</span>';
                            break;
                        case 'Pending':
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Pending</span>';
                            break;
                        case 'Rejected':
                            $label = '<span class="badge bg-label-warning" text-capitalized="">Rejected</span>';
                            break;
                    }
                    return $label;
                })
                ->addColumn('created_at', function ($model) {
                    return $model->created_at ?? '';
                })
                ->editColumn('action', function ($model) {
                    return view('admin.leaves.leaves-action', ['leaves' => $model])->render();
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('company')) {
                        $company = $request->company;
                            $query->collection = $query->collection->filter(function ($record) use ($company) {
                                return str_contains(strtolower($record['company']), strtolower($company));
                        });
                    }
                    if ($request->has('filter_status')) {
                        $status = $request->filter_status;
                            $query->collection = $query->collection->filter(function ($record) use ($status) {
                                return str_contains(strtolower($record['status']), strtolower($status));
                        });
                    }
                    if ($request->has('date_range')) {
                        $date_range = $request->date_range;
                        if (!empty($date_range)) {
                            if (strpos($date_range, ' to ') !== false) {
                                list($start_date, $end_date) = explode(' to ', $date_range);
                                if (!empty($start_date) && !empty($end_date)) {
                                    $query->collection = $query->collection->filter(function ($record) use ($start_date, $end_date) {
                                        $created_at = strtotime($record['created_at']);
                                        $start_date_timestamp = strtotime($start_date);
                                        $end_date_timestamp = strtotime($end_date);
                                        return $created_at >= $start_date_timestamp && $created_at <= $end_date_timestamp;
                                    });
                                }
                            } else {
                                $query->collection = $query->collection->filter(function ($record) use ($date_range) {
                                    $created_at = strtotime($record['created_at']);
                                    $selected_date = strtotime($date_range);
                                    return $created_at === $selected_date;
                                });
                            }
                        }
                    }
                })
                ->rawColumns(['company', 'user', 'start_at', 'duration', 'behavior_type', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('admin.leaves.index', compact('data'));
    }

    public function show(string $id){
        $company = $request->company ?? '';
        $userLeave = getUserLeaveDetail($id);
        if(isset($userLeave) && !empty($userLeave)){
            if(view()->exists('admin.leaves.show')){
                return view('admin.leaves.show', compact('userLeave', 'company'))->render();
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }
}
