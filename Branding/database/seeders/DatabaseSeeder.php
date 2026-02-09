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
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@sorex.co.id', // Email khusus admin
            'password' => Hash::make('admin123'), // Password admin
            'role' => 'admin',
            'regional' => 'ALL', // Admin bisa akses semua (bebas diisi)
        ]);

        // User::create([
        //     'name' => 'Admin',
        //     'email' => env('ADMIN_EMAIL'),
        //     'password' => Hash::make(env('ADMIN_PASSWORD')),
        //     'role' => 'admin',
        //     'regional' => 'ALL',
        // ]);
    }
}
