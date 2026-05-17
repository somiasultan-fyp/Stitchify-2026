<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@stitchify.com'],
            [
                'name'      => 'Super Admin',
                'phone'     => '03000000000',
                'password'  => Hash::make('admin123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );
    }
}