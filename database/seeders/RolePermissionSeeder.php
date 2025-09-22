<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus cache spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',

            'sale.view',
            'sale.create',
            'sale.update',
            'sale.delete',

            'payment.view',
            'payment.create',
            'payment.update',
            'payment.delete',

            'item.view',
            'item.create',
            'item.update',
            'item.delete',

            'user.view',
            'user.create',
            'user.update',
            'user.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user  = Role::firstOrCreate(['name' => 'user']);

        $admin->givePermissionTo(Permission::all());

        $user->givePermissionTo([
            'dashboard.view',
            'sale.view',
            'sale.create',
            'sale.update',
            'sale.delete',
            'payment.view',
            'payment.create',
            'payment.update',
            'payment.delete',
            'item.view',
        ]);
    }
}
