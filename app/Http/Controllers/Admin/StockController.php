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
                    $label = '';

                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-warning" text-capitalized="">Pending</span>';
                            break;
                        case 2:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Approved</span>';
                            break;
                        case 3:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Rejected</span>';
                            break;
                    }

                    return $label;
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
}
