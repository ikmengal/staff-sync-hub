<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('permissions')->truncate();
    DB::table('model_has_permissions')->truncate();
    DB::table('role_has_permissions')->truncate();
    $permissions = [
      [
        'label' =>  'Dashboard',
        'name' => 'dashboards-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],

      [
        'label' =>  'User',
        'name' => 'users-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'User',
        'name' => 'users-profile',
        'display_name' => 'Profile',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'User',
        'name' => 'users-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'User',
        'name' => 'users-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'User',
        'name' => 'users-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'User',
        'name' => 'users-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'User',
        'name' => 'users-direct-permission',
        'display_name' => 'Users Direct Permission',
        'guard_name' => 'web'
      ],

      [
        'label' =>  'Permission',
        'name' => 'permissions-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Permission',
        'name' => 'permissions-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Permission',
        'name' => 'permissions-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Permission',
        'name' => 'permissions-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Permission',
        'name' => 'permissions-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Role',
        'name' => 'roles-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Role',
        'name' => 'roles-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Role',
        'name' => 'roles-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Role',
        'name' => 'roles-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'label' =>  'Role',
        'name' => 'roles-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],


      [
        'label' =>  'Role',
        'name' => 'roles-all-user',
        'display_name' => 'User',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employee',
        'name' => 'employees-list',
        'display_name' => 'Employee',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employee',
        'name' => 'employees-terminated-current-month',
        'display_name' => 'Terminated current month',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employee',
        'name' => 'employees-new-hired-employee',
        'display_name' => 'New hired employee',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Purchase',
        'name' => 'purchases-list',
        'display_name' => 'List',
        'guard_name' => 'web'

      ],
      [
        'label' => 'Purchase Requests',
        'name' => 'purchase-requests-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Purchase Requests',
        'name' => 'purchase-requests-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Purchase Requests',
        'name' => 'purchase-requests-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Purchase Requests',
        'name' => 'purchase-requests-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Purchase Requests',
        'name' => 'purchase-requests-status',
        'display_name' => 'Status',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Offices',
        'name' => 'offices-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Estimates',
        'name' => 'estimates-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Estimates',
        'name' => 'estimates-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Receipts',
        'name' => 'receipts-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],

      [
        'label' => 'Settings',
        'name' => 'settings-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employees',
        'name' => 'employees-terminated',
        'display_name' => 'terminated',
        'guard_name' => 'web'
      ],
      [
        'label' => 'users',
        'name' => 'users-edit-password',
        'display_name' => 'edit',
        'guard_name' => 'web'
      ],
      [
        'label' => 'attendances',
        'name' => 'attendances-show-companies',
        'display_name' => 'Show companies',
        'guard_name' => 'web'
      ],
      [
        'label' => 'attendances',
        'name' => 'attendances-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'pre-employees',
        'name' => 'pre-employees-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'pre-employees',
        'name' => 'pre-employees-view',
        'display_name' => 'View',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employee letters',
        'name' => 'employee-letters-list',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employee letters',
        'name' => 'employee-letters-create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employee letters',
        'name' => 'employee-letters-edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employee letters',
        'name' => 'employee-letters-delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Employee letters',
        'name' => 'employee-letters-status',
        'display_name' => 'Status',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Discrepencies',
        'name' => 'discrepencies-List',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Discrepencies',
        'name' => 'discrepencies-Create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Discrepencies',
        'name' => 'discrepencies-Edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Discrepencies',
        'name' => 'discrepencies-Delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Discrepencies',
        'name' => 'discrepencies-Status',
        'display_name' => 'Status',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Attendance-adjustments',
        'name' => 'Attendance-adjustments-List',
        'display_name' => 'List',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Attendance-adjustments',
        'name' => 'Attendance-adjustments-Create',
        'display_name' => 'Create',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Attendance-adjustments',
        'name' => 'Attendance-adjustments-Edit',
        'display_name' => 'Edit',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Attendance-adjustments',
        'name' => 'Attendance-adjustments-Delete',
        'display_name' => 'Delete',
        'guard_name' => 'web'
      ],
      [
        'label' => 'Attendance-adjustments',
        'name' => 'Attendance-adjustments-Status',
        'display_name' => 'Status',
        'guard_name' => 'web'
      ]
      
    ];

    foreach ($permissions as $value) {

      Permission::create([
        'label' => $value['label'],
        'name' => $value['name'],
        'display_name' => $value['display_name'],
        'guard_name' => $value['guard_name'],
      ]);
    }
  }
}
