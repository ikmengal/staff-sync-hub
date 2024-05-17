<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ValideIPAddress;
use App\Models\User;

class ValideIPAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('ip-addresses-list');
        $title = 'All IP Address';
        $records = ValideIPAddress::select("*");

        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->editColumn('user', function ($model) {
                    return getUserName2($model->creator) ?? '';
                })
                ->addColumn('ip_address', function ($model) {
                    return '<span class="text-primary fw-semibold">'.$model->ip_address.'</span>';
                })
                ->addColumn('status', function ($model) {
                    $label = '';
                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-Active</span>';
                            break;
                    }
                    return $label;
                })
                ->addColumn('created_at', function ($model) {
                    return date('F d, Y',strtotime($model->created_at)) ?? '';
                })
                ->editColumn('action', function ($model) {
                    return view('admin.ip-addresses.action', ['model' => $model])->render();
                })
                // ->filter(function ($query) use ($request) {
                //     if ($request->has('company')) {
                //         $company = $request->company;
                //             $query->collection = $query->collection->filter(function ($record) use ($company) {
                //                 return str_contains(strtolower($record['company']), strtolower($company));
                //         });
                //     }
                //     if ($request->has('filter_status')) {
                //         $status = $request->filter_status;
                //             $query->collection = $query->collection->filter(function ($record) use ($status) {
                //                 return str_contains(strtolower($record['status']), strtolower($status));
                //         });
                //     }
                //     if ($request->has('date_range')) {
                //         $date_range = $request->date_range;
                //         if (!empty($date_range)) {
                //             if (strpos($date_range, ' to ') !== false) {
                //                 list($start_date, $end_date) = explode(' to ', $date_range);
                //                 if (!empty($start_date) && !empty($end_date)) {
                //                     $query->collection = $query->collection->filter(function ($record) use ($start_date, $end_date) {
                //                         $created_at = strtotime($record['created_at']);
                //                         $start_date_timestamp = strtotime($start_date);
                //                         $end_date_timestamp = strtotime($end_date);
                //                         return $created_at >= $start_date_timestamp && $created_at <= $end_date_timestamp;
                //                     });
                //                 }
                //             } else {
                //                 $query->collection = $query->collection->filter(function ($record) use ($date_range) {
                //                     $created_at = strtotime($record['created_at']);
                //                     $selected_date = strtotime($date_range);
                //                     return $created_at === $selected_date;
                //                 });
                //             }
                //         }
                //     }
                // })
                ->rawColumns(['user', 'ip_address', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('admin.ip-addresses.index', compact('title'));
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
        $this->authorize('ip-addresses-create');

        $rules = [
            'name' => 'required:unique:valide_i_p_addresses,ip_address',
        ];

        $message = [
            'name.unique' => 'This IP Address has already been taken.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        
        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()->toArray(), 'validation' => false];
        }

        if (!empty($request->name)) {
            $result = ValideIPAddress::create([
                'creator_id' =>  Auth::id() ?? null,
                'ip_address' =>  $request->name ?? null,
            ]);
        }

        if (isset($result) && !empty($result)) {
            return ['success' => true, 'message' => 'IP Address created successfully', 'status' => 200];
        } else {
            return ['success' => false, 'message' => 'IP Address not created, something went wrong', 'status' => 501];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('ip-addresses-delete');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('ip-addresses-edit');

        $ipAddress = ValideIPAddress::where('id', $id)->first();
        if (isset($ipAddress) && !empty($ipAddress)) {
            $view = view('admin.ip-addresses.edit', compact('ipAddress'))->render();
            return ['success' => true, 'view' => $view];
        } else {
            return ['success' => false, 'message' => 'IP Address not found'];
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('ip-addresses-create');

        $rules = [
            'name' => 'required:unique:valide_i_p_addresses,ip_address',
        ];

        $message = [
            'name.unique' => 'This IP Address has already been taken.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        
        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()->toArray(), 'validation' => false];
        }

        $ipAddress = ValideIPAddress::findOrFail($id);

        if(isset($ipAddress) && !empty($ipAddress)){
            if (!empty($request->name)) {
                $result = $ipAddress->update([
                    'creator_id' =>  Auth::id() ?? null,
                    'ip_address' =>  $request->name ?? null,
                ]);
            }
    
            if (isset($result) && !empty($result)) {
                return ['success' => true, 'message' => 'IP Address updated successfully', 'status' => 200];
            } else {
                return ['success' => false, 'message' => 'IP Address not updated, something went wrong', 'status' => 501];
            }
        }else {
            return ['success' => false, 'message' => 'No record found, something went wrong', 'status' => 501];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('ip-addresses-delete');

        $ipAddress = ValideIPAddress::findOrFail($id);

        if(isset($ipAddress) && !empty($ipAddress)){
            
            $result = $ipAddress->delete();
            
            if (isset($result) && !empty($result)) {
                return ['success' => true, 'message' => 'IP Address deleted successfully', 'status' => 200];
            } else {
                return ['success' => false, 'message' => 'IP Address not deleted, something went wrong', 'status' => 501];
            }
        }else {
            return ['success' => false, 'message' => 'No record found, something went wrong', 'status' => 501];
        }
    }
}
