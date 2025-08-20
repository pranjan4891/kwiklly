<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendor_admins')->insert([
            'uuid' => Str::uuid(),
            'user_type' => 'admin',
            'email' => 'admin@kwiklly.com',
            'password' => Hash::make('123456'),
            'name' => 'Kwiklly',
            'status' => 1,
            'is_active' => 1,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
