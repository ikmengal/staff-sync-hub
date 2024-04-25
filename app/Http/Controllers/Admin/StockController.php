<?php

namespace App\Http\Controllers\Admin;

use DB;
use Str;
use App\Models\User;
use App\Models\Stock;
use App\Models\Company;
use App\Models\StockImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('stock-list');
        $title = 'All Stocks';

        $model = Stock::orderby('id', 'desc')->get();
        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('title', function ($model) {
                    return $model->title;
                })
                ->editColumn('description', function ($model) {
                    return $model->description;
                })
                ->editColumn('creator', function ($model) {
                    return $model->hasUser->first_name.' '.$model->hasUser->last_name;
                })
                ->editColumn('company', function ($model) {
                    return $model->hasCompany->name;
                })
                ->editColumn('quantity', function ($model) {
                    return $model->quantity;
                })
                ->editColumn('status', function ($model) {
                    return view('admin.stocks.status', ['model' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.stocks.action', ['model' => $model])->render();
                })
                ->rawColumns(['title', 'description', 'creator', 'company', 'quantity', 'status', 'action'])
                ->make(true);
        }

        return view('admin.stocks.index', compact('title'));
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
        $title = 'Stock Detail';
        $stock = Stock::where('id', $id)->first();
        return view('admin.stocks.show', compact('title', 'stock'));
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
        $data = [];
        $stock = Stock::find($request->stock_status_id);
        if($stock){
            $updated = $stock->update([
                'remarks' => $request->remark, 
                'status' => $request->status_data,
            ]);

            if($updated){
                return response()->json(['success' => true, "message" => 'Stock status Updated successfully' ?? null], 200);
            }else{
                return response()->json(['success' => true, "message" => 'Stock status not updated successfully' ?? null], 401);
            }
        }else{
            return response()->json(['success' => true, "message" => 'No record found' ?? null], 401);
        }
    }
}
