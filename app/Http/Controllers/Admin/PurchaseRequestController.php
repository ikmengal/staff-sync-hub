<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('purchases-request');
        $data['title'] = 'Purchase Requests';
        $data['companies'] = Company::get();
        $records = PurchaseRequest::select("*");
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('company', function ($model) {
                    return $model->company->name ?? '';
                })
                ->addColumn('subject', function ($model) {
                    return $model->subject;
                })
                ->addColumn('description', function ($model) {
                    $description = $model->description;
                    $words = str_word_count($description);
                    $limit = 5;
                    if ($words > $limit) {
                        $wordsArray = explode(' ', $description, $limit + 1);
                        $description = implode(' ', array_slice($wordsArray, 0, $limit)) . '...';
                        $description .= '<a href="' . route("purchase-requests.show", $model->id) . '"> Read more</a>';
                    }
                    return $description;
                })
                ->addColumn('creator', function ($model) {
                    return $model->creator ?? '';
                })
                ->addColumn('status', function ($model) {
                    // $data = '';
                    // $class = $model->getStatus->class  ?? "primary";
                    // $name = $model->getStatus->name  ?? "-";
                    // $data .= '<span class="badge bg-label-' . $class  . '">' . $name   . '</span>';
                    // return $data;
                    $data = '';
                    $class = $model->getStatus->class  ?? "primary";
                    $name = $model->getStatus->name  ?? "-";
                    $data .= '<span class="badge bg-label-' . $class  . '">' . $name   . '</span>';
                    return $data;
                })
                ->addColumn('action', function ($model) {
                    return view('admin.purchase-requests.action', ['model' => $model])->render();
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request['search'])) {
                        $search = $request['search'];
                        $instance->where('subject', "LIKE", "%$search%");
                        $instance->orWhere('description', "LIKE", "%$search%");
                        $instance->orWhere('creator', "LIKE", "%$search%");
                    }

                    if(!empty($request['company'])){
                        $search = $request['company'];
                        $instance->where('company_id', $search);
                    }

                    if(!empty($request['filter_status'])){
                        $search = $request['filter_status'];
                        $instance->where('status', $search);
                    }
                })
                ->rawColumns(['title', 'description', 'creator', 'company',   'status', 'action'])
                ->make(true);
        }
        return view('admin.purchase-requests.index',  $data);
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
    public function show(string $id)
    {
        $title = 'Purchase Request Detail';
        $record = PurchaseRequest::where('id', $id)->first();
        if(isset($record) && !empty($record)){
            if (view()->exists('admin.purchase-requests.show')) {
                return view('admin.purchase-requests.show', compact('record', 'title'));
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

    public function status(Request $request)
    {
        $record = PurchaseRequest::find($request->purchase_status_id);
        $userId = Auth()->id();
        if($record){
            DB::beginTransaction();
            try {
                $record->status = $request->status_data;
                $record->remarks = $request->remark;
                $record->modified_by = $userId;
                $record->modified_at = now();
                $record->save();
                DB::commit();
                return response()->json(['success' => true, "message" => 'Purchase status updated successfully'], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['success' => false, "message" => $e->getMessage()], 401);
            }
        }else{
            DB::rollback();
            return response()->json(['success' => false, "message" => 'No record found'], 401);
        }
    }
}