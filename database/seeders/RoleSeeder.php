<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::updateOrCreate(['name' => Role::ADMIN], ['label' => 'Admin']);
        $kasir = Role::updateOrCreate(['name' => Role::KASIR], ['label' => 'Kasir']);

        User::updateOrCreate(
            ['email' => 'admin@qashya.test'],
            ['name' => 'Admin Qashya', 'password' => Hash::make('admin123'), 'role_id' => $admin->id]
        );

        User::updateOrCreate(
            ['email' => 'kasir@qashya.test'],
            ['name' => 'Kasir Qashya', 'password' => Hash::make('kasir123'), 'role_id' => $kasir->id]
        );
    }
}