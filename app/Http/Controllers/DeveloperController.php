<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function getCompanyEmployees(){
        return getCurrentWeekAttendance();
    }
    public function getAttendanceReport(){
        return $regulars = getDailyAttendanceReport();
        // return $lastValue = end($regulars);
    }
    public function getCompanies(){
        // return getAllCompanies();
        return getAllTerminatedEmployeesOfCurrentMonth()['all_terminated_employees_of_current_month'];
        // return $terminated_employees;
        return getAllCompaniesVehicles()['vehicles'];
    }

    //Inject search data to json file dynamically..
    public function generateMenuData()
    {
        // Define your array data with the base URL injected
        $icon = "ti-smart-home";
        $menuData = [
            "pages" => [
                [
                    "name" => "All Offices",
                    "icon" => $icon,
                    "url" => route('admin.companies')
                ],
                [
                    "name" => "All Employees",
                    "icon" => $icon,
                    "url" => route('admin.companies.employees')
                ],
                [
                    "name" => "All New Hired Employees",
                    "icon" => $icon,
                    "url" => route('admin.companies.employees.new_hiring')
                ],
                [
                    "name" => "All Terminated Employees",
                    "icon" => $icon,
                    "url" => route('admin.companies.terminated_employees')
                ],
                [
                    "name" => "Terminated Employees of current month",
                    "icon" => $icon,
                    "url" => route('admin.companies.terminated_employees_of_current_month')
                ],
                [
                    "name" => "All Vehicles",
                    "icon" => $icon,
                    "url" => route('admin.companies.vehicles')
                ],
            ]
        ];

        // Encode the array data as JSON
        $jsonData = json_encode($menuData);

        // Write the JSON data to a file
        // file_put_contents('menuData.json', $jsonData);
        file_put_contents(base_path('public/admin/assets/json/search-vertical.json'), $jsonData);

        // Optionally, you can return a success message or perform other actions
        return response()->json(['message' => 'Menu data generated successfully']);
    }
}
