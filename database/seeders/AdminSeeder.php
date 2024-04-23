<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\WorkShift;
use App\Models\Department;
use App\Models\JobHistory;
use App\Models\Designation;
use App\Models\DepartmentUser;
use Illuminate\Database\Seeder;
use App\Models\EmploymentStatus;
use App\Models\WorkingShiftUser;
use Spatie\Permission\Models\Role;
use App\Models\UserEmploymentStatus;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Setting::create([
            'name' => 'Demo',
            'banner' => '1684433621.png',
            'language' => 'English',
            'country' => 'Pakistan',
        ]);

        $admin = User::create([
            'is_employee' => 0,
            'slug' => 'super-admin-1',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'super@admin.com',
            'status' => 1,
            'password' => Hash::make('admin@123'),
        ]);

        $roles = [
            'Super Admin', 'Admin'
        ];

        foreach($roles as $role) {
            Role::create(
                [
                    'name' => $role,
                    'guard_name' => 'web',
                ]
            );
        }
        $admin_role = Role::where('name', 'Super Admin')->first();

        $permissions = include(config_path('seederData/permissions.php'));

        foreach ($permissions as $permission) {
            $underscoreSeparated = explode('-', $permission);
            $label = str_replace('_', ' ', $underscoreSeparated[0]);
            Permission::create([
                'label' => $label,
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
        //Assign Permissions and Role "Admin User".
        $permissions = Permission::get();
        $admin_role->givePermissionTo($permissions);
        $admin->assignRole($admin_role);
        //Admin Permissions

        $designations = include(config_path('seederData/designations.php'));

        $department = Department::create([
            'manager_id' => $admin->id,
            'name' => "Main Department",
            'status' => 1,
        ]);

        DepartmentUser::create([
            'department_id' => $department->id,
            'user_id' => $admin->id,
            'start_date' => date('Y-m-d'),
        ]);

        foreach($designations as $designation) {
            Designation::create(
                [
                    'title' => $designation,
                    'description' => $designation,
                ]
            );
        }

        $designation = Designation::where('title', 'Vice President - Business Unit Head')->first();

        $employment_statuses = include(config_path('seederData/employment_statuses.php'));

        $is_default = 0;
        foreach($employment_statuses as $employment_status) {
            $underscoreSeparated = explode('-', $employment_status);
            $label = $underscoreSeparated[0];
            $class = $underscoreSeparated[1];

            if($label=='Probation'){
                $is_default = 1;
            }
            EmploymentStatus::create([
                'name' => $label,
                'class' => $class,
                'alias' => $label,
                'description' => $label,
                'is_default' => $is_default,
            ]);
        }

        $employment_status = EmploymentStatus::where('name', 'Permanent')->first();

        JobHistory::create([
            'created_by' => $admin->id,
            'user_id' => $admin->id,
            'designation_id' => $designation->id,
            'employment_status_id' => $employment_status->id,
            'joining_date' => date('Y-m-d'),
            'end_date' => null,
        ]);

        UserEmploymentStatus::create([
            'user_id' => $admin->id,
            'employment_status_id' => $employment_status->id,
            'start_date' => date('Y-m-d'),
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'employment_id' => 1145,
            'joining_date' => date('Y-m-d'),
        ]);

        $work_shift = WorkShift::create([
            'name' => 'Night Shift (9 to 6)',
            'start_date' => date('Y-m-d'),
            'type' => 'regular',
            'status' => 1,
            'is_default' => 1,
        ]);

        WorkingShiftUser::create([
            'working_shift_id' => $work_shift->id,
            'user_id' => $admin->id,
            'start_date' => date('Y-m-d'),
        ]);
    }
}
