<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\WorkShiftController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EmployeeRequisitionController;
use App\Http\Controllers\Admin\EstimateController;
use App\Http\Controllers\Admin\MasterLoginController;
use App\Http\Controllers\Admin\PurchaseRequestController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Models\PurchaseRequest;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get("create-user" , function() {
//     return User::create([

//         "first_name" => "Admin",
//         "last_name" => "-",
//         "email" => "admin@gmail.com",
//         "password" => Hash::make("admin@123"),
//         "is_employee" => 1,
//         "slug" => Str::slug("-" , "Admin ")
//     ]);
// });
//Resource Routes
Route::get("check-config", function () {
    $config =  config("project.companies");

    return  $config;
});

//cache clear
Route::get('/cache-clear', function () {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    $cache = 'Route cache cleared <br /> View cache cleared <br /> Cache cleared <br /> Config cleared <br /> Config cache cleared';
    return $cache;
});
//cache clear

//Custom Routes
Route::get('/developer/test', [DeveloperController::class, 'getCompanyEmployees'])->name('developer.test');

Route::get('/', function () {
    return redirect()->route('admin.login');
});
Route::get('admin/login', [AdminController::class, 'loginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login');
//Custom Routes



//Authentication Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/get-counter-data/{box}', [AdminController::class, 'getCounterData'])->name('admin.get-counter-data');
    Route::get('/admin/get-attendance-counter-data/{counter_key}/{json_key}', [AdminController::class, 'getAttendanceCounterData'])->name('admin.get-attendance-counter-data');
    Route::get('/admin/get-slider-data', [AdminController::class, 'getSliderData'])->name('admin.get-slider-data');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/logout', [AdminController::class, 'logOut'])->name('user.logout');

    //
    Route::get('admin/companies', [AdminController::class, 'getCompanies'])->name('admin.companies');
    Route::get('admin/companies/employees', [AdminController::class, 'getCompaniesEmployees'])->name('admin.companies.employees');
    Route::get('admin/companies/employees/new_hiring', [AdminController::class, 'getCompaniesEmployeesNewHiring'])->name('admin.companies.employees.new_hiring');
    Route::get('admin/companies/terminated_employees', [AdminController::class, 'getCompaniesTerminatedEmployees'])->name('admin.companies.terminated_employees');
    Route::get('admin/companies/terminated_employees_of_current_month', [AdminController::class, 'getCompaniesTerminatedEmployeesOfCurrentMonth'])->name('admin.companies.terminated_employees_of_current_month');
    Route::get('admin/company/employees/{company}', [AdminController::class, 'getCompanyEmployees'])->name('admin.company.employees');
    Route::get('admin/companies/vehicles', [AdminController::class, 'getCompaniesVehicles'])->name('admin.companies.vehicles');
    Route::get('admin/company/vehicles/{company}', [AdminController::class, 'getCompanyVehicles'])->name('admin.company.vehicles');
    Route::get('admin/company/filter', [AdminController::class, 'getSearchDataOnLoad'])->name('admin.companies.getSearchDataOnLoad');

    //inject search urls data to json file
    Route::get('/get-menu-data', [DeveloperController::class, 'generateMenuData']);

    Route::post('receipt-status', [ReceiptController::class, 'status'])->name('receipts.status');
    Route::get('receipt-filter', [ReceiptController::class, 'getSearchDataOnLoad'])->name('receipts.getSearchDataOnLoad');

    Route::get('users/direct-permision/{id}', [UserController::class, 'directPermission'])->name('users.directPermission');
    Route::post('users/store-direct-permision', [UserController::class, 'storeDirectPermission'])->name('users.storeDirectPermission');

    // Purchase Request Route
    Route::post('purchase-requests-status', [PurchaseRequestController::class, 'status'])->name('purchase-requests.status');
 

    Route::post('approve-estimate', [EstimateController::class, 'approve'])->name('estimates.approve');
 

    Route::resource('/estimates', EstimateController::class);
    Route::resource('/purchase-requests', PurchaseRequestController::class);
    Route::resource('/requisitions', EmployeeRequisitionController::class);
    Route::resource('/settings', SettingController::class);
    Route::resource('/departments', DepartmentController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);
    Route::resource('/designations', DesignationController::class);
    Route::resource('/work_shifts', WorkShiftController::class);
    Route::resource('/receipts', ReceiptController::class);
    Route::resource('users', UserController::class);

    // Master Login
    Route::get("master-login/{company_id}", [MasterLoginController::class, "login"])->name("master.login");
});
//Authentication Routes

require __DIR__ . '/auth.php';










