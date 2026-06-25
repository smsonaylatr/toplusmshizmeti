<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin kullanıcı
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // Customer kullanıcı
        User::updateOrCreate(
            ['email' => 'customer@customer.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('customer123'),
                'is_admin' => false,
            ]
        );
    }
}
