<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Models\{
    SalaryHistory, MonthlySalaryReport,
};

class SalaryReportController extends Controller
{
    public function salaryReports(Request $request){
        $this->authorize('salary-reports-list');
        $title = 'Salary Reports';

        $allCompanySummaries = collect();

        foreach (getAllCompanies() as $company) {
            $companyProfile = [
                'favicon' => $company->base_url.'/public/admin/assets/img/favicon/'.$company->favicon,
                'name' => $company['name'],
                'email' => $company['email'],
            ];

            // Fetch the date range for the company
            $firstMonthYear = MonthlySalaryReport::on($company['portalDb'])
                ->orderBy('month_year', 'asc')
                ->value('month_year');

            $lastMonthYear = MonthlySalaryReport::on($company['portalDb'])
                ->orderBy('month_year', 'desc')
                ->value('month_year');

            if ($firstMonthYear && $lastMonthYear) {
                $companySummary = MonthlySalaryReport::on($company['portalDb'])
                    ->whereBetween('month_year', [$firstMonthYear, $lastMonthYear])
                    ->select(
                        DB::raw('SUM(actual_salary) as total_actual_salary'),
                        DB::raw('SUM(car_allowance) as total_car_allowance'),
                        DB::raw('SUM(deduction) as total_deduction'),
                        DB::raw('SUM(net_salary) as total_net_salary')
                    )
                    ->first();

                $companySummary->company = $companyProfile;
                $companySummary->company_key = $company->company_key;

                $allCompanySummaries->push($companySummary);
            }
        }
        
        // Add formatted numbers to the summaries
        $allCompanySummaries = $allCompanySummaries->map(function ($item) {
            $item->total_actual_salary = humanReadableNumber($item->total_actual_salary);
            $item->total_car_allowance = humanReadableNumber($item->total_car_allowance);
            $item->total_deduction = humanReadableNumber($item->total_deduction);
            $item->total_net_salary = humanReadableNumber($item->total_net_salary);
            return $item;
        });
        
        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($allCompanySummaries)
                ->addIndexColumn()
                ->addColumn('company', function ($companySummary) {
                    $company = $companySummary->company;
                    if(!empty($company)){
                        return view('admin.salary-reports.company-profile', ['company' => $company])->render();
                    }else{
                        return 'N/A';
                    }
                })
                ->addColumn('total_actual_salary', function ($companySummary) {
                    return 'PKR. <strong>'. $companySummary->total_actual_salary. '<strong>';
                })
                ->addColumn('total_car_allowance', function ($companySummary) {
                    return 'PKR. <strong>'. $companySummary->total_car_allowance. '<strong>';
                })
                ->addColumn('total_deduction', function ($companySummary) {
                    return 'PKR. <strong>'. $companySummary->total_deduction. '<strong>';
                })
                ->addColumn('total_net_salary', function ($companySummary) {
                    return 'PKR. <strong>'. $companySummary->total_net_salary. '<strong>';
                })
                ->addColumn('action', function ($companySummary) {
                    return view('admin.salary-reports.action', ['model' => $companySummary])->render();
                })
                ->rawColumns(['company', 'total_actual_salary', 'total_car_allowance', 'total_deduction', 'total_net_salary', 'action'])
                ->make(true);
        }

        return view('admin.salary-reports.salary-reports', get_defined_vars());
    }
    public function salaryReportDetails(Request $request, $company_key){
        $this->authorize('salary-reports-list');
        $title = 'Salary Reports Details';
        
        $allSalaryReports = collect();
        foreach(getAllCompanies() as $company) {
            if(isset($company_key) && !empty($company_key) && $company_key==$company->company_key){
                $salaryReports = attendanceReport($company['portalDb']);
                foreach ($salaryReports as $report) {
                    if(isset($request->month_year) && !empty($request->month_year) && $request->month_year==$report->month_year){
                        $existingReport = $allSalaryReports->firstWhere('month_year', $request->month_year);
                        if ($existingReport) {
                            $existingReport->total_actual_salary += $report->total_actual_salary;
                            $existingReport->total_car_allowance += $report->total_car_allowance;
                            $existingReport->total_deduction += $report->total_deduction;
                            $existingReport->total_net_salary += $report->total_net_salary;
                        } else {
                            $allSalaryReports->push($report);
                        }
                    }elseif(empty($request->month_year)){
                        $existingReport = $allSalaryReports->firstWhere('month_year', $report->month_year);
                        if ($existingReport) {
                            $existingReport->total_actual_salary += $report->total_actual_salary;
                            $existingReport->total_car_allowance += $report->total_car_allowance;
                            $existingReport->total_deduction += $report->total_deduction;
                            $existingReport->total_net_salary += $report->total_net_salary;
                        } else {
                            $allSalaryReports->push($report);
                        }
                    }
                }
            }
        }
        // Add unique company names as a comma-separated string and format numbers
        $allSalaryReports = $allSalaryReports->map(function ($item) {
            // Format numbers to be more human-readable
            $item->total_actual_salary = humanReadableNumber($item->total_actual_salary);
            $item->total_car_allowance = humanReadableNumber($item->total_car_allowance);
            $item->total_deduction = humanReadableNumber($item->total_deduction);
            $item->total_net_salary = humanReadableNumber($item->total_net_salary);
            return $item;
        });

        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($allSalaryReports)
                ->addIndexColumn()
                ->addColumn('month_year', function ($salaryReport) {
                    if(!empty($salaryReport->month_year)){
                        $explodeMonthYear = explode('/', $salaryReport->month_year);
                        $month = $explodeMonthYear[0];
                        $year = $explodeMonthYear[1];
                        $monthYear = \Carbon\Carbon::create($year, $month, 1);
                        return date('M, Y', strtotime($monthYear));
                    }else{
                        return '-';
                    }
                })
                ->addColumn('total_actual_salary', function ($salaryReport) {
                    return 'PKR. <strong>'. $salaryReport->total_actual_salary. '<strong>';
                })
                ->addColumn('total_car_allowance', function ($salaryReport) {
                    return 'PKR. <strong>'. $salaryReport->total_car_allowance. '<strong>';
                })
                ->addColumn('total_deduction', function ($salaryReport) {
                    return 'PKR. <strong>'. $salaryReport->total_deduction. '<strong>';
                })
                ->addColumn('total_net_salary', function ($salaryReport) {
                    return 'PKR. <strong>'. $salaryReport->total_net_salary. '<strong>';
                })
                 ->rawColumns(['month_year', 'total_actual_salary', 'total_car_allowance', 'total_deduction', 'total_net_salary'])
                ->make(true);
        }

        return view('admin.salary-reports.salary-report-details', get_defined_vars());
    }
}
