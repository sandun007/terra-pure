<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default System Administrator
        Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@terrapure.com',
                'phone' => '0771234567',
                'address' => 'Head Office, Sri Lanka',
                'img' => 'default.png',
                'password' => 'admin123',
                'view_password' => 'admin123',
            ]
        );

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@terrapure.com',
                'password' => Hash::make('admin123'),
                'view_password' => 'admin123',
                'type' => 'admin',
                'seller_type' => 'system',
                'is_wholesale_seller' => 0,
                'is_wholesaler' => 0,
            ]
        );
    }
}
