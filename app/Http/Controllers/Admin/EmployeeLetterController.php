<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeLetter;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use PDF;

class EmployeeLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('employee-letters-list');
        $data = [];
        $data['title'] = 'All Employees Letters';
        if ($request->ajax() && $request->loaddata == "yes") {
            $records = getEmployeesLetters();
            return DataTables::of($records['employee_letters'])
                ->addIndexColumn()
                ->addColumn('company', function ($model) {
                    return $model->company ?? '';
                })
                ->addColumn('title', function ($model) {
                    return formatLetterTitle($model->title) ?? '';
                })
                ->editColumn('effective_date', function ($model) {
                    return $model->effective_date ?? '';
                })
                ->editColumn('validity_date', function ($model) {
                    return $model->validity_date ?? '';
                })
                ->addColumn('employee_id', function ($model) {
                    return view('admin.employee-letters.employee-profile', ['model' => $model->hasEmployee])->render();
                })
                ->addColumn('created_at', function ($model) {
                    return $model->created_at ?? '';
                })
                ->editColumn('action', function ($model) {
                    return view('admin.employee-letters.action', ['model' => $model])->render();
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('company')) {
                        $company = $request->company;
                        $query->collection = $query->collection->filter(function ($record) use ($company) {
                            return str_contains(strtolower($record['company']), strtolower($company));
                        });
                    }
                })
                ->rawColumns(['company', 'employee_id', 'title', 'effective_date', 'validity_date', 'created_at', 'action'])
                ->make(true);
        }
        return view('admin.employee-letters.index', compact('data'));
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
        $employee_letter = getEmployeeLetterDetail($id);
        if (isset($employee_letter) && !empty($employee_letter)) {
            if (view()->exists('admin.employee-letters.show')) {
                if($employee_letter->title=="joining_letter"){
                    $model = $this->joiningLetterData($employee_letter);
                    return view('admin.employee-letters.joining_letter', compact('model', 'employee_letter', 'company'))->render();
                }elseif($employee_letter->title=="vehical_letter"){
                    $model = $this->vehicleLetterData($employee_letter);
                    return (string) view('admin.employee-letters.vehicle_letter', compact('model', 'employee_letter', 'company'));
                }elseif($employee_letter->title=="promotion_letter"){
                    $model = $this->promotionLetterData($employee_letter);
                    return (string) view('admin.employee-letters.promotion_letter', compact('model', 'employee_letter', 'company'));
                }
            
            
                //return view('admin.employee-letters.show', compact('model', 'company'))->render();
            } else {
                abort(404);
            }
        } else {
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

    public function getSearchDataOnLoad(){
        $data['companies'] = Company::get();
        return ['success' => true, 'message' => null, 'data' => $data, 'status' => 200];
    }

    public function joiningLetterData($employee_letter){
        $employee_name = '';
        if(isset($employee_letter->hasEmployee) && !empty($employee_letter->hasEmployee->first_name)){
            $employee_name = $employee_letter->hasEmployee->first_name.' '.$employee_letter->hasEmployee->last_name;
        }
        
        $is_vehicle = '';
        $vehicle_cc = '';
        if(isset($employee_letter->hasEmployee) && !empty($employee_letter->hasEmployee->is_vehicle)){
            $is_vehicle = $employee_letter->hasEmployee->is_vehicle;
            $vehicle_cc = $employee_letter->hasEmployee->jobHistory->vehicle_cc;
        }

        $employee_designation = '';
        if(isset($employee_letter->hasEmployee->joiningDesignation->designation) && !empty($employee_letter->hasEmployee->joiningDesignation->designation->title)){
            $employee_designation = $employee_letter->hasEmployee->joiningDesignation->designation->title;
        }

        $employee_department = '';
        if(isset($employee_letter->hasEmployee->joiningDepartmentBridge->department) && !empty($employee_letter->hasEmployee->joiningDepartmentBridge->department->name)){
            $employee_department = $employee_letter->hasEmployee->joiningDepartmentBridge->department->name;
        }

        $employee_salary = 0;
        if(isset($employee_letter->hasEmployee->joiningSalary) && !empty($employee_letter->hasEmployee->joiningSalary->salary)){
            $employee_salary = $employee_letter->hasEmployee->joiningSalary->salary;
        }

        $employee_salary_in_words = '';
        if(isset($employee_letter->hasEmployee->joiningSalary) && !empty($employee_letter->hasEmployee->joiningSalary->salary)){
            $employee_salary_in_words = $this->convertNumberToText($employee_letter->hasEmployee->joiningSalary->salary);
        }

        $reporting_name = '';
        if(isset($employee_letter->hasEmployee->joiningDepartmentBridge->department->manager) && !empty($employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->first_name)){
            $reporting_name = $employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->first_name.' '.$employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->last_name;
        }

        $reporting_designation = '';
        if(isset($employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->jobHistory->designation) && !empty($employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->jobHistory->designation->title)){
            $reporting_designation = $employee_letter->hasEmployee->joiningDepartmentBridge->department->manager->jobHistory->designation->title;
        }

        $reporting_department = '';
        if(isset($employee_letter->hasEmployee->joiningDepartmentBridge->department) && !empty($employee_letter->hasEmployee->joiningDepartmentBridge->department->name)){
            $reporting_department = $employee_letter->hasEmployee->joiningDepartmentBridge->department->name;
        }


        $model = [
            'title' => formatLetterTitle($employee_letter->title),
            'effective_date' => date('d, M Y', strtotime($employee_letter->effective_date)),
            'is_vehicle' => $is_vehicle,
            'vehicle_cc' => $vehicle_cc,
            'name' => $employee_name,
            'designation' => $employee_designation,
            'department' => $employee_department,
            'salary' => number_format($employee_salary),
            'salary_in_words' => $employee_salary_in_words,
            'reporting_name' => $reporting_name,
            'reporting_designation' => $reporting_designation,
            'reporting_department' => $reporting_department,
            'validity_date' => date('d, M Y', strtotime($employee_letter->validity_date)),
        ];
        
        return (object)$model;
    }

    public function vehicleLetterData($employee_letter){
        $employee_name = '';
        $employee_cnic = '';
        if(isset($employee_letter->hasEmployee) && !empty($employee_letter->hasEmployee->first_name)){
            $employee_name = $employee_letter->hasEmployee->first_name.' '.$employee_letter->hasEmployee->last_name;
            $employee_cnic = $employee_letter->hasEmployee->profile->cnic;
        }

        $vehicle_name = '';
        $vehicle_reg_number = '';
        if(isset($employee_letter->hasUserVehicle->hasVehicle) && !empty($employee_letter->hasUserVehicle->hasVehicle->name)){
            $vehicle_name = $employee_letter->hasUserVehicle->hasVehicle->name;
            $vehicle_reg_number = $employee_letter->hasUserVehicle->hasVehicle->registration_number;
        }


        $model = [
            'title' => formatLetterTitle($employee_letter->title),
            'effective_date' => date('d, F Y', strtotime($employee_letter->effective_date)),
            'name' => $employee_name,
            'cnic' => $employee_cnic,
            'vehicle_name' => $vehicle_name,
            'vehicle_reg_number' => $vehicle_reg_number,
        ];

        return (object)$model;
    }

    public function promotionLetterData($employee_letter){
        $employee_name = '';
        if(isset($employee_letter->hasEmployee) && !empty($employee_letter->hasEmployee->first_name)){
            $employee_name = $employee_letter->hasEmployee->first_name.' '.$employee_letter->hasEmployee->last_name;
        }

        $employee_designation = '';
        if(isset($employee_letter->hasEmployee->jobHistory->designation) && !empty($employee_letter->hasEmployee->jobHistory->designation->title)){
            $employee_designation = $employee_letter->hasEmployee->jobHistory->designation->title;
        }

        $employee_department = '';
        if(isset($employee_letter->hasEmployee->departmentBridge->department) && !empty($employee_letter->hasEmployee->departmentBridge->department->name)){
            $employee_department = $employee_letter->hasEmployee->departmentBridge->department->name;
        }

        $current_salary = 0;
        if(isset($employee_letter->hasEmployee->salaryHistory) && !empty($employee_letter->hasEmployee->salaryHistory->salary)){
            $current_salary = $employee_letter->hasEmployee->salaryHistory->salary;
        }

        $raise_salary = 0;
        if(isset($employee_letter->hasEmployee->salaryHistory) && !empty($employee_letter->hasEmployee->salaryHistory->salary)){
            $raise_salary = $employee_letter->hasEmployee->salaryHistory->raise_salary;
        }

        $employee_salary_in_words = '';
        if(isset($employee_letter->hasEmployee->salaryHistory) && !empty($employee_letter->hasEmployee->salaryHistory->salary)){
            $employee_salary_in_words = $this->convertNumberToText($employee_letter->hasEmployee->salaryHistory->salary);
        }

        $vehicle_name = '';
        if(isset($employee_letter->hasEmployee->jobHistory) && !empty($employee_letter->hasEmployee->jobHistory->vehicle_name)){
            $vehicle_name = $employee_letter->hasEmployee->jobHistory->vehicle_name;
        }

        $vehicle_cc = '';
        if(isset($employee_letter->hasEmployee->jobHistory) && !empty($employee_letter->hasEmployee->jobHistory->vehicle_cc)){
            $vehicle_cc = $employee_letter->hasEmployee->jobHistory->vehicle_cc;
        }

        $salary_percentage = 0;
        if(isset($employee_letter->hasEmployee->salaryHistory) && !empty($employee_letter->hasEmployee->salaryHistory->salary)){
            $salary_percentage = ($employee_letter->hasEmployee->salaryHistory->raise_salary/$employee_letter->hasEmployee->salaryHistory->salary)*100;
        }

        $model = [
            'title' => formatLetterTitle($employee_letter->title),
            'effective_date' => date('F, d Y', strtotime($employee_letter->effective_date)),
            'name' => $employee_name,
            'employee_designation' => $employee_designation,
            'employee_department' => $employee_department,
            'raise_salary' => number_format($raise_salary),
            'salary' => number_format($current_salary),
            'employee_salary_in_words' => $employee_salary_in_words,
            'vehicle_name' => $vehicle_name,
            'vehicle_cc' => $vehicle_cc,
            'increased_percent' => number_format($salary_percentage, 2),
        ];

        return (object)$model;
    }

    public function downloadLetter($employee_letter_id, $company = null){
        $employee_letter = getEmployeeLetterDetail($employee_letter_id);
        $company = $company ?? null;
        if($employee_letter->title=="joining_letter"){
            $model = $this->joiningLetterData($employee_letter);
            $pdf = PDF::loadView('admin.employee-letters.joining-pdf-letter', compact('model', 'company'));
        }elseif($employee_letter->title=="vehical_letter"){
            $model = $this->vehicleLetterData($employee_letter);
            $pdf = PDF::loadView('admin.employee-letters.vehicle-pdf-letter', compact('model', 'company'));
        }elseif($employee_letter->title=="promotion_letter"){
            $model = $this->promotionLetterData($employee_letter);
            $pdf = PDF::loadView('admin.employee-letters.promotion-pdf-letter', compact('model', 'company'));
        }

        $download_file_name = $employee_letter->title;
        return $pdf->download($download_file_name.'.pdf');
    }

    function convertNumberToText($num = '')
    {
        $num    = (string) ((int) $num);

        if ((int) ($num) && ctype_digit($num)) {
            $words  = array();

            $num    = str_replace(array(',', ' '), '', trim($num));

            $list1  = array(
                '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
                'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
                'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
            );

            $list2  = array(
                '', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty',
                'seventy', 'eighty', 'ninety', 'hundred'
            );

            $list3  = array(
                '', 'thousand', 'million', 'billion', 'trillion',
                'quadrillion', 'quintillion', 'sextillion', 'septillion',
                'octillion', 'nonillion', 'decillion', 'undecillion',
                'duodecillion', 'tredecillion', 'quattuordecillion',
                'quindecillion', 'sexdecillion', 'septendecillion',
                'octodecillion', 'novemdecillion', 'vigintillion'
            );

            $num_length = strlen($num);
            $levels = (int) (($num_length + 2) / 3);
            $max_length = $levels * 3;
            $num    = substr('00' . $num, -$max_length);
            $num_levels = str_split($num, 3);

            foreach ($num_levels as $num_part) {
                $levels--;
                $hundreds   = (int) ($num_part / 100);
                $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
                $tens       = (int) ($num_part % 100);
                $singles    = '';

                if ($tens < 20) {
                    $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
                } else {
                    $tens = (int) ($tens / 10);
                    $tens = ' ' . $list2[$tens] . ' ';
                    $singles = (int) ($num_part % 10);
                    $singles = ' ' . $list1[$singles] . ' ';
                }
                $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
            }
            $commas = count($words);
            if ($commas > 1) {
                $commas = $commas - 1;
            }

            $words  = implode(', ', $words);

            $words  = trim(str_replace(' ,', ',', ucwords($words)), ', ');
            if ($commas) {
                $words  = str_replace(',', ' and', $words);
            }

            return $words;
        } else if (!((int) $num)) {
            return 'Zero';
        }
        return '';
    }
}

