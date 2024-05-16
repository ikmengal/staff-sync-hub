<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PreEmployee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    protected $model;
    protected $moduleName;

    public function __construct()
    {
        $this->model = "\App\Models\PreEmployee";
        $this->moduleName = "Pre Employees";
    }
    public function index(Request $request)
    {
        $this->authorize("pre-employees-list");
        $title = 'All Pre-Employees';
        $companies = companies();
        $company = $request->company;
        if ($request->ajax() && $request->loaddata == "yes") {
            $model = getPreEmployees()['pre_employees'];
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('applied_position', function ($model) {
                    if (isset($model->title) && !empty($model->title)) {
                        $label = '<b class="text-primary">' . $model->title . '</b>';
                        return '<span class="text-primary fw-semibold">' . strip_tags($label) . '</span';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('expected_salary', function ($model) {
                    if (isset($model->expected_salary) && !empty($model->expected_salary)) {
                        $expected_salary_label = 'PKR. <b>' . $model->expected_salary  . '</b>';
                        return '<span class="fw-semibold">' . strip_tags($expected_salary_label) . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('is_exist', function ($model) {
                    $label = '';
                    switch ($model->is_exist) {
                        case 1:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">Duplicate</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Current</span>';
                            break;
                    }

                    return $label;
                })
                ->addColumn('status', function ($model) {

                    return $model->status;
                })
                ->addColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->addColumn('manager_id', function ($model) {
                    return view('admin.companies.pre-employees.partials.manager-profile', ['employee' => $model->employee])->render();
                })
                ->editColumn('name', function ($model) {

                    return view('admin.companies.pre-employees.profile', ['employee' => $model->employee])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.companies.pre-employees.action', ['employee' => $model->employee, 'company' => $model->company_key])->render();
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('company')) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($model) use ($company) {
                            return str_contains(strtolower($model['company']), strtolower($company));
                        });
                    }
                })
                ->rawColumns(['status', 'is_exist', 'name', 'manager_id', 'applied_position', 'expected_salary', 'action'])
                ->make(true);
        }
        return view('admin.companies.pre-employees.index', compact('title'));
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
        $this->authorize("pre-employees-list");
        foreach (companies() as $index => $portalDb) {
            if (isset($request->company) && $request->company == $index) {
                $model = PreEmployee::on($portalDb)->with('haveReferences')->where('id', $id)->first();
            }
        }
        if (isset($model->user->profile->profile) && !empty($model->user->profile->profile)) {
            $profile_img = $model->user->profile->profile ?? null;
        } else {
            $profile_img = isset($model->profile_image) && !empty($model->profile_image) ? $model->profile_image : null;
        }
        if (isset($model->user->profile->cnic_front) && !empty($model->user->profile->cnic_front)) {
            $cnic_front = $model->user->profile->cnic_front ?? null;
        } else {
            $cnic_front = isset($model->cnic_front) && !empty($model->cnic_front) ? $model->cnic_front : null;
        }
        if (isset($model->user->profile->cnic_back) && !empty($model->user->profile->cnic_back)) {
            $cnic_back = $model->user->profile->cnic_back ?? null;
        } else {
            $cnic_back = isset($model->cnic_back) && !empty($model->cnic_back) ? $model->cnic_back : null;
        }
        if (isset($model) && $model->form_type == 1) {
            $title = 'Show Employee Details';
            return view('admin.companies.pre-employees.show', compact('model', 'title', 'profile_img', 'cnic_front', 'cnic_back'));
        }
        // return view('admin.companies.pre-employees.office_boys.show', compact('model', 'title', 'profile_img', 'cnic_front', 'cnic_back'));
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

    public function exportPreEmployee(Request $request)
    {

      

        $response = new StreamedResponse(function () use ($request) {
            // Open output stream

            $company = $request->company;
            $month = $request->month;
            $year = $request->year;
            $slug = $request->slug;
         
            $handle = fopen('php://output', 'w');
            // Add CSV headers
            fputcsv($handle, [
                "#",
                'Applicant',
                'Applied Position',
                'Expected Salary',
                'Manager',
                'Applied At',
                'Status',
                'Is Exist',
             

            ]);

            // Query to get Leaddata for all users within the specified date range
          
            $pre_employees =  getPreEmployees()['pre_employees'];
    
            // Loop through each user's monthly Leaddata
            foreach ($pre_employees as $index => $pre_employee) {
                // Access the data for each user
              
                $id = ++$index;
               
                $applicant =  $pre_employee->employee->name.' '.$pre_employee->employee->father_name;
                $applied_position = $pre_employee->title;
                $expected_salary = $pre_employee->expected_salary;
                if(!empty($pre_employee->employee->hasManager)){
                    $manager =  $pre_employee->employee->hasManager->first_name.' '.$pre_employee->employee->hasManager->last_name;
                }else{
                    $manager = '-';
                }
                $applied_at = $pre_employee->created_at;
                $status = $pre_employee->status;
                $is_exist = "-";
                if($pre_employee->is_exist == 1) {
                    $is_exist = 'Duplicate';
                }else{
                    $is_exist = 'Current';

                }
                // Add a new row with data
                fputcsv($handle, [
                    $id,
                    $applicant,
                    $applied_position,
                    $expected_salary,
                    $manager,
                    $applied_at,
                    $status,
                    $is_exist

              
                ]);
            }
            // Close the output stream
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' ,
        ]);


        return $response;
    }
}
