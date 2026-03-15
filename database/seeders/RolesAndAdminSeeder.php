<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndAdminSeeder extends Seeder
{
    public const FIXED_ROLES = [
        'ADMIN',
        'CEO',
        'CREATIVES',
        'CORE',
        'SOCIAL_MEDIA_MANAGER',
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Minimal perms (optional; expand later)
        $permissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create fixed roles
        foreach (self::FIXED_ROLES as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Give ADMIN all permissions
        $adminRole = Role::where('name', 'ADMIN')->firstOrFail();
        $adminRole->syncPermissions($permissions);

        // Create admin user
        $adminEmail = 'admin@example.com';

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin@12345'),
            ]
        );

        if (!$admin->hasRole('ADMIN')) {
            $admin->assignRole('ADMIN');
        }
    }
}