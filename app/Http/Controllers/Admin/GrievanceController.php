<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Grievance;
use App\Models\User;
use App\Models\Company;

class GrievanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [];
        $data['title'] = 'All Companies Grievances';

        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getGrievances();
            return DataTables::of($records['grievances'])
                ->addIndexColumn()
                ->addColumn('company', function ($model) {
                    return $model->company ?? '';
                })
                ->editColumn('name', function ($model) {
                    return $model->user->first_name. ' ' .$model->user->last_name ?? '';
                })
                ->addColumn('description', function ($model) {
                    return $model->description ?? '';
                })
                ->addColumn('anonymous', function ($model) {
                    return $model->anonymous ?? '';
                })
                ->addColumn('status', function ($model) {
                    return $model->status  ?? '';
                })
                ->addColumn('created_at', function ($model) {
                    return $model->created_at ?? '';
                })
                ->editColumn('action', function ($model) {
                    return view('admin.grievances.grievances-action', ['grievance' => $model])->render();
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
                ->rawColumns(['company', 'name', 'description', 'anonymous', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('admin.grievances.index', compact('data'));
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
        $gravience = getGrievanceDetail($id);
        if(isset($gravience) && !empty($gravience)){
            if(view()->exists('admin.grievances.show')){
                return view('admin.grievances.show', compact('gravience', 'company'))->render();
            }else{
                abort(404);
            }
        }else{
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
        return ['success' => true, 'message' => null, 'data' => $data, 'status' => 200];
    }
}
