<?php

use App\Models\User;
use App\Models\Setting;
use App\Models\WorkShift;
use App\Models\VehicleUser;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

function companies(){
    $poraltalsDbConnections = [
        'cyberonix' => env('CYBERONIX_DB_DATABASE'),
        'vertical' => env('VERTICAL_DB_DATABASE'),
        'braincell' => env('BRAINCELL_DB_DATABASE'),
        'clevel' => env('CLEVEL_DB_DATABASE'),
        'delve' => env('DELVE12_DB_DATABASE'),
        'horizontal' => env('HORIZONTAL_DB_DATABASE'),
        'mercury' => env('MERCURY_DB_DATABASE'),
        'momyom' => env('MOMYOM_DB_DATABASE'),
        'softnova' => env('SOFTNOVA_DB_DATABASE'),
        'softfellow' => env('SOFTFELLOW_DB_DATABASE'),
        'swyftcube' => env('SWYFTCUBE_DB_DATABASE'),
        'swyftzone' => env('SWYFTZONE_DB_DATABASE'),
        'techcomrade' => env('TECHCOMRADE_DB_DATABASE'),
    ];

    return $poraltalsDbConnections;
}
function getAllCompanies(){
    $companies = [];

    foreach(companies() as $portalName=>$portalDb){
        $settings = Setting::on($portalDb)->select(['id', 'base_url', 'name', 'phone_number', 'email', 'favicon'])->first();
        $total_employees = User::on($portalDb)->where('is_employee', 1)->with('profile')->select(['id', 'slug', 'first_name', 'last_name', 'email'])->get();
        $settings['portalDb'] = $portalDb;
        $settings['total_employees'] = $total_employees;
        $companies[$portalName] = $settings;
    }

    return $companies;
}
function getAllCompaniesEmployees(){
    $data = [];
    $allEmployees = [];
    $total_employees_count = 0;
    foreach(getAllCompanies() as $company){
        $total_employees_count += count($company->total_employees);
        foreach($company->total_employees as $employee){
            $profile = '';
            if(!empty($employee->profile->profile)){
                $profile = $employee->profile->profile;
            }
            $allEmployees[] = (object)[
                'slug' => $employee->slug,
                'base_url' => $company->base_url,
                'avatar_path' => '/public/admin/assets/img/avatars/',
                'profile' => $profile,
                'name' => $employee->first_name.' '.$employee->last_name,
                'email' => $employee->email,
            ];
        }
    }
    // Shuffle the array
    shuffle($allEmployees);

    // Number of records you want to retrieve
    $numRecords = 6; // Change this to the number of records you want to retrieve
    // Get a random selection of records
    $data['shaffleEmployees'] = array_slice($allEmployees, 0, $numRecords);
    $data['total_employees_count'] = $total_employees_count;
    $data['plusEmployees'] = $total_employees_count-$numRecords;

    return $data;
}

function getAllCompaniesVehicles(){
    $data = [];
    $allCompaniesVehicles = [];

    foreach(getAllCompanies() as $portalName=>$portalDb){
        $vehicleUsers = VehicleUser::on($portalDb->portalDb)->with('hasVehicle', 'hasVehicle.hasImage', 'hasUser.profile')->where('end_date', null)->where('status', 1)->select(['vehicle_id', 'user_id', 'deliver_date'])->get();
        $setting['vehicles'] = $vehicleUsers;
        $setting['total_employees'] = count($portalDb->total_employees);
        $setting['base_url'] = $portalDb->base_url;
        $allCompaniesVehicles[$portalName] = $setting;
    }
    $vehicles = [];
    $totalEmployees = 0;
    foreach($allCompaniesVehicles as $companyVehicles){
        $totalEmployees += $companyVehicles['total_employees'];
        foreach($companyVehicles['vehicles'] as $companyVehicle){
            $profile = '';
            if(isset($companyVehicle->hasUser->profile) && !empty($companyVehicle->hasUser->profile->profile)){
                $profile = $companyVehicle->hasUser->profile->profile;
            }
            $vehicleName = '';
            $vehicleThumbnail = '';
            if(isset($companyVehicle->hasVehicle) && !empty($companyVehicle->hasVehicle->name)){
                $vehicleName = $companyVehicle->hasVehicle->name;
                $vehicleThumbnail = $companyVehicle->hasVehicle->thumbnail;
            }

            $vehicles[] = (object)[
                'base_url' => $companyVehicles['base_url'],
                'avatar_path' => '/public/admin/assets/img/avatars/',
                'profile' => $profile,
                'employeeName' => $companyVehicle->hasUser->first_name.' '.$companyVehicle->hasUser->last_name,
                'vehicleName' => $vehicleName,
                'vehicleThumbnail' => $vehicleThumbnail,
            ];
        }
    }
    $data['vehicles'] = $vehicles;
    $data['totalEmployees'] = $totalEmployees;
    $data['successGoalPercent'] = number_format(count($vehicles)/$totalEmployees*100, 2);
    return $data;
}

function settings()
{
    return Setting::first();
}

function defaultShift()
{
    return WorkShift::where('is_default', 1)->where('status', 1)->first();
}

function appName()
{
    $setting = Setting::first();
    if (isset($setting) && !empty($setting->name)) {
        $app_name = $setting->name;
    } else {
        $app_name = '-';
    }

    return $app_name;
}
function SubPermissions($label)
{
    return Permission::where('label', $label)->get();
}

function loginUser(){
    if(Auth::check()){
        $profile = null;
        if(isset(Auth::user()->profile) && !empty(Auth::user()->profile->profile)){
            $profile = Auth::user()->profile->profile;
        }
        $user = (object)[
            'id' => Auth::user()->id,
            'slug' => Auth::user()->slug,
            'name' => Auth::user()->first_name.' '.Auth::user()->last_name,
            'email' => Auth::user()->email,
            'profile' => $profile,
            'role' => Auth::user()->getRoleNames()->first(),
        ];

        return $user;
    }else{
        return null;
    }
}

function getUserData($user){
    $profile = null;
    if(isset($user->profile) && !empty($user->profile->profile)){
        $profile = $user->profile->profile;
    }
    $designation = '-';
    if (isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title)){
        $designation = $user->jobHistory->designation->title;
    }
    $user = (object)[
        'id' => $user->id,
        'slug' => $user->slug,
        'name' => $user->first_name.' '.$user->last_name,
        'email' => $user->email,
        'profile' => $profile,
        'role' => $user->getRoleNames()->first(),
        'designation' => $designation,
    ];

    return $user;
}
