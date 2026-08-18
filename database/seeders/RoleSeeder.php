<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            'create-post',
            'edit-post',
            'delete-post',
            'create-comment',
            'edit-comment',
            'delete-comment',
            'ban-user',
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $userRole = Role::where('name', 'user')->first();
        $userRole->givePermissionTo(['create-post', 'edit-post', 'delete-post', 'create-comment', 'edit-comment', 'delete-comment']);

        $moderatorRole = Role::where('name', 'moderator')->first();
        $moderatorRole->givePermissionTo(['create-post', 'edit-post', 'delete-post', 'create-comment', 'edit-comment', 'delete-comment', 'ban-user']);

        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->givePermissionTo($permissions);
    }
}
