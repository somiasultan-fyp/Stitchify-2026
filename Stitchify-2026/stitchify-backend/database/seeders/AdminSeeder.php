<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user banao
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@stitchify.com',
            'password' => Hash::make('admin@123'),  // password: admin@123
            'role'     => 'admin',
            'phone'    => '03000000000' ,
            'is_active'=> true,
        ]);

        echo "✅ Admin created: admin@stitchify.com / admin@123\n";
    }
}
