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
              'group' =>  'Dashboard',
              'name' => 'dashboards-view',
              'display_name' => 'View',
              'guard_name' => 'web'
            ],
            
            [
              'group' =>  'User',
              'name' => 'users-list',
              'display_name' => 'List',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'User',
              'name' => 'users-create',
              'display_name' => 'Create',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'User',
              'name' => 'users-view',
              'display_name' => 'View',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'User',
              'name' => 'users-edit',
              'display_name' => 'Edit',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'User',
              'name' => 'users-delete',
              'display_name' => 'Delete',
              'guard_name' => 'web'
            ],
      
            [
              'group' =>  'Permission',
              'name' => 'permissions-list',
              'display_name' => 'List',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Permission',
              'name' => 'permissions-create',
              'display_name' => 'Create',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Permission',
              'name' => 'permissions-view',
              'display_name' => 'View',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Permission',
              'name' => 'permissions-edit',
              'display_name' => 'Edit',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Permission',
              'name' => 'permissions-delete',
              'display_name' => 'Delete',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Role',
              'name' => 'roles-list',
              'display_name' => 'List',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Role',
              'name' => 'roles-create',
              'display_name' => 'Create',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Role',
              'name' => 'roles-view',
              'display_name' => 'View',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Role',
              'name' => 'roles-edit',
              'display_name' => 'Edit',
              'guard_name' => 'web'
            ],
            [
              'group' =>  'Role',
              'name' => 'roles-delete',
              'display_name' => 'Delete',
              'guard_name' => 'web'
            ],
           

            [
              'group' =>  'Role',
              'name' => 'roles-all-user',
              'display_name' => 'User',
              'guard_name' => 'web'
            ],
           
        ];

        foreach($permissions as $value) {
            Permission::create([
                'group' => $value['group'],
                'name' => $value['name'],
                'display_name' => $value['display_name'],
                'guard_name' => $value['guard_name'],
            ]);
        }
    }
}
