<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
                    $data = '';
                    $class = $model->getStatus->class  ?? "primary";
                    $name = $model->getStatus->name  ?? "-";
                    $data .= '<span class="badge bg-label-' . $class  . '">' . $name   . '</span>';
                    return $data;
                })
                ->addColumn('action', function ($model) {
                })
                ->filter(function ($query) use ($request) {
                   
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
        //
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
