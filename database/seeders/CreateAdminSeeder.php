<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@resto.id'],
            [
                'name' => 'Admin Resto',
                'password' => Hash::make('Admin123'),
                'role' => 'owner',
                'permissions' => User::defaultPermissions('owner'),
                'email_verified_at' => now(),
            ]
        );
    }
}
