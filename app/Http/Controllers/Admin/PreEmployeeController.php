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
         $this->authorize("pres-employee-list");
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
                        $expected_salary_label = 'PKR. <b>' . number_format($model->expected_salary) . '</b>';
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
                    $label = '';
                    if (isset($model->status) && !empty($model->status)) {
                    $label = '<span class="badge bg-label-danger" text-capitalized="">Rejected</span>';
                    }
                    return $label;
                })
                ->addColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->addColumn('manager_id', function ($model) {
                    return $model->manager_id;
                })
                ->editColumn('name', function ($model) {
   
                    return view('admin.companies.pre-employees.profile', ['employee' => $model->employee])->render();
                })
                ->addColumn('action', function ($model) {
                    return view('admin.companies.pre-employees.action', ['employee' => $model->employee,'company'=>$model->company_key])->render();
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('company')) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($model) use ($company) {
                            return str_contains(strtolower($model['company']), strtolower($company));
                        });
                    
                    }
                })
                ->rawColumns(['status', 'is_exist', 'name', 'manager_id', 'applied_position', 'expected_salary','action'])
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
    public function show(Request $request,string $id)
    {
        $this->authorize("pres-employee-view");
        foreach(companies() as $index => $portalDb){
            if(isset($request->company) && $request->company == $portalDb){
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
            // else {
            //     $title = 'Show Office Boy Details';
            // return view('admin.office_boys.show', compact('model', 'title', 'profile_img', 'cnic_front', 'cnic_back'));
            // }
        
    
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

    public function exportPreEmployee(Request $request){

        $company = $request->company;
        $month = $request->month;
        $year = $request->year;
        $slug = $request->slug;

        $response = new StreamedResponse(function () use($ids){
            // Open output stream
            $handle = fopen('php://output', 'w');
            // Add CSV headers
            fputcsv($handle, [
                "#",
                'Brand',
                'Team',
                'Creator',
                'Follwup Agent',
                'Assignee', 
                'DBA',
                'Number Of Equpments', 
                'Customer Email',
                'Customer Full Name',
                'Customer Primary Number',
                'Customer Secondary Number',
                'Country', 
                'State',
                'City',
                'Busines Address', 
                'Postal Code',
                'Latest Remarks', 
                'Last Schedule Date',
                'Last Updated',
                'Overdue',
                'Current Status'

            ]);
    
            // Query to get Leaddata for all users within the specified date range
            $leads = $this->model::with('team','latestFollowUp','leadStatus')->whereIn('id', $ids)->orderBy('id','desc')->get();
            // Loop through each user's monthly Leaddata
            foreach ($leads as $index => $lead) {
                // Access the data for each user
                $id = ++$index;
                $dba = $lead->dba;
                $brand = getBrand($lead->brand_id);
                $customer_name = $lead->customer->full_name ?? "-";
                $customer_email = $lead->customer->email ?? "-";
                $customer_primary_phone = $lead->customer->primary_phone_number ?? "-";
                $customer_secondary_phone = $lead->customer->secondary_phone_number ?? "-";
                $creator =  getUserName(getUser($lead->creator_id));
                $team =  (!empty($lead->team)) ? $lead->team->name : "-";
                $followupAgent = getUserName(getUser($lead->followup_agent_id));
                $assignee = getUserName(getUser($lead->assignee_1));
                $equipments = $lead->leadDetails()->count();
                $country = getCountryOnBrand($lead->brand_id)->name;
                $state =  getState($lead->state_id)->name;
                $city = getCity($lead->city_id)->name;
                $address = $lead->address;
                $postal_code = $lead->postal_code;
                $remarks = (!empty($lead->latestFollowUp)) ? $lead->latestFollowUp->remarks : '-';
                $last_schedule_date_time = formatDateTime($lead->last_schedule_date_time);
                $last_updated_date = formatDateTime($lead->updated_at);
                $overdue = ($lead->last_schedule_date < Carbon::now()->toDateString()) ? "YES" : "NO";
                $status = $lead->leadStatus->name;
    
                // Add a new row with data
                fputcsv($handle, [
                    $id,
                    $brand, 
                    $team, 
                    $creator,
                    $followupAgent,
                    $assignee,
                    $dba, 
                    $equipments,
                    $customer_email, 
                    $customer_name, 
                    $customer_primary_phone,  
                    $customer_secondary_phone, 
                    $country,
                    $state,
                    $city,
                    $address,
                    $postal_code,
                    $remarks,
                    $last_schedule_date_time,
                    $last_updated_date,
                    $overdue,
                    $status
                ]);
               
            }
            // Close the output stream
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
        ]);


        return $response;

    }
}
