<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssignPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('id', 1)->first();
        $permissions = Permission::pluck('name')->toArray();
        if(isset($role) && !empty($role) && isset($permissions) && !empty($permissions)) {
            $role->syncPermissions($permissions);
        }
    }
}
