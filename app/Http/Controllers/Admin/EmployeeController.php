<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Request $request, string $slug)
    {
        $data['title'] = 'Employee Detail';
        
        $records = collect(getAllCompaniesEmployees()['total_employees']);
        $model = $records->first(function ($model) use ($slug) {
            return $model->slug === $slug;
        });
        if(isset($model) && !empty($model)){
            if(view()->exists('admin.companies.employees.employee-show')){ 
                $company = $model;
                $companyName = explode(' ',$model->company);
                $companyName = strtolower($companyName[0]) ?? '';
                $model = getEmployeeDetails($companyName, $model->slug);
                return view('admin.companies.employees.employee-show', compact('model', 'data', 'company'));
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
}
