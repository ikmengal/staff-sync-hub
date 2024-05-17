<?php

use App\Models\{
    User, PurchaseRequest 
};
use Illuminate\Support\Facades\{
    Route, Artisan 
};
use App\Http\Controllers\{
    DeveloperController
};
use App\Http\Controllers\Admin\{
    RoleController, UserController, AdminController, SalaryController, ProfileController, ReceiptController, SettingController, EmployeeController, EstimateController,
    WorkShiftController, AttendanceController, DepartmentController, PermissionController, DesignationController, MasterLoginController, PreEmployeeController,
    PurchaseRequestController, EmployeeRequisitionController, AttendanceAdjustmentController, GrievanceController, UserLeaveController, SalaryReportController
};

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
    Route::get('admin/attendance/report',[AttendanceController::class,'attendanceReport'])->name('admin.attendance.report');
    Route::get('admin/companies', [AdminController::class, 'getCompanies'])->name('admin.companies');
    Route::get('admin/companies/employees', [AdminController::class, 'getCompaniesEmployees'])->name('admin.companies.employees');
    Route::get('admin/companies/employees/new_hiring', [AdminController::class, 'getCompaniesEmployeesNewHiring'])->name('admin.companies.employees.new_hiring');
    Route::get('admin/companies/terminated_employees', [AdminController::class, 'getCompaniesTerminatedEmployees'])->name('admin.companies.terminated_employees');
    Route::get('admin/companies/terminated_employees_of_current_month', [AdminController::class, 'getCompaniesTerminatedEmployeesOfCurrentMonth'])->name('admin.companies.terminated_employees_of_current_month');
    Route::get('admin/company/employees/{company}', [AdminController::class, 'getCompanyEmployees'])->name('admin.company.employees');
    Route::get('admin/companies/vehicles', [AdminController::class, 'getCompaniesVehicles'])->name('admin.companies.vehicles');
    Route::get('admin/company/vehicles/{company}', [AdminController::class, 'getCompanyVehicles'])->name('admin.company.vehicles');
    Route::get('admin/company/filter', [AdminController::class, 'getSearchDataOnLoad'])->name('admin.companies.getSearchDataOnLoad');
    Route::get('admin/employees/show{slug?}', [EmployeeController::class, 'show'])->name('admin.employees.show');
    Route::get('admin/companies/attendance',[AttendanceController::class,'allCompanies'])->name('admin.companies.list');
    Route::get('admin/company/attendance/{company?}/{getMonth?}/{getYear?}/{getUser?}/',[AttendanceController::class,'companyAttendance'])->name('admin.companies.attendance');
    Route::get('admin/company/attendance/export',[AttendanceController::class,'exportCompanyAttendance'])->name('admin.companies.attendance.export');
    Route::get("admin/company/get-compnany-employees",[AttendanceController::class,'getCompanyEmployees'])->name('admin.get.company.employees');
    // Route::get('admin/company/attendance/filter',[AttendanceController::class,'monthlyAttendanceReportfgdfg'])->name('admin.company.attendance.filter');
 
    Route::get('admin/company/attendance/summary/{company}/{getMonth?}/{getYear?}/{getUser?}/',[AdminController::class,'attendanceSummary'])->name('admin.companies.attendance.summary');

    // Route::get('admin/employees/show/{slug}', [EmployeeController::class, 'show'])->name('admin.employees.show');

    //inject search urls data to json file
    Route::get('/get-menu-data', [DeveloperController::class, 'generateMenuData']);

    Route::post('receipt-status', [ReceiptController::class, 'status'])->name('receipts.status');
    Route::get('receipt-filter', [ReceiptController::class, 'getSearchDataOnLoad'])->name('receipts.getSearchDataOnLoad');

    Route::get('users/direct-permision/{id}', [UserController::class, 'directPermission'])->name('users.directPermission');
    Route::post('users/store-direct-permision', [UserController::class, 'storeDirectPermission'])->name('users.storeDirectPermission');

    // Purchase Request Route
    Route::post('purchase-requests-status', [PurchaseRequestController::class, 'status'])->name('purchase-requests.status');
 
    Route::get('show-all-roles', [RoleController::class, 'showAllUsers'])->name('roles.showAllUsers');

    Route::post('approve-estimate', [EstimateController::class, 'approve'])->name('estimates.approve');
 
    Route::get('users-edit-password',[UserController::class,'updatePasswordForm'])->name('users.update.password.form');
    Route::post('users-update-password',[UserController::class,'updatePassword'])->name('users.updatePassword');
    Route::get('show-all-roles', [RoleController::class, 'showAllUsers'])->name('roles.showAllUsers');
   
    Route::get('users-search-data',[UserController::class,'getSearchData'])->name('users.search.data');

    //salary details
    Route::get('salaries/details',[SalaryController::class,'salaryDetails'])->name('salaries.detail');
    Route::get('salaries/generate-salary-slip',[SalaryController::class,'generateSalarySlip'])->name('salaries.generate.salary.slip');
    

    //Pre Employee
    Route::get('pre-employees/export',[PreEmployeeController::class,'exportPreEmployee'])->name('pre-employees.export');

    // Grievences filter route
    Route::get('grievance-filter', [GrievanceController::class, 'getSearchDataOnLoad'])->name('grievance.getSearchDataOnLoad');

    //Salary Repory
    Route::controller(SalaryReportController::class)->group(function(){
        Route::get('admin/salary-reports','salaryReports')->name('admin.salary-reports');
        Route::get('admin/salary-reports/details','salaryReportDetails')->name('admin.salary-reports.details');
    });
    //Salary Repory

    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);
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
    Route::resource('employees', EmployeeController::class);
    Route::resource('/mark_attendance', AttendanceAdjustmentController::class);
    Route::resource('/pre-employees',PreEmployeeController::class);
    Route::resource('salaries',SalaryController::class);
    Route::resource('/user_leaves', UserLeaveController::class);
    Route::resource('/grievances', GrievanceController::class);

    // Master Login
    Route::get("master-login/{company_id}", [MasterLoginController::class, "login"])->name("master.login");
});
//Authentication Routes

require __DIR__ . '/auth.php';

