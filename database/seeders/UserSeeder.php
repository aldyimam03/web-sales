<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => 'admin' . $i,
                'email' => 'admin' . $i . '@example.com',
                'password' => Hash::make('password'),
            ]);
            $user->assignRole($adminRole);
        }

        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
            ]);
            $user->assignRole($userRole);
        }
    }
}
