<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@simrit.test'],
            [
                'name'       => 'Super Admin',
                'password'   => Hash::make('password'),
                'role'       => 'superadmin',
                'is_active'  => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@simrit.test'],
            [
                'name'       => 'Admin SIMRIT',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
                'is_active'  => true,
            ]
        );
    }
}
