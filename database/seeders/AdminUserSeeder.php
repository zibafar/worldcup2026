<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@rashtgold.test';

        $data = [
            'name' => 'مدیر رشت‌گلد',
            'email' => $email,
            'password' => Hash::make('Admin@123456'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('users', 'mobile')) {
            $data['mobile'] = '09120000000';
        }

        if (Schema::hasColumn('users', 'is_active')) {
            $data['is_active'] = true;
        }

        if (Schema::hasColumn('users', 'email_verified_at')) {
            $data['email_verified_at'] = now();
        }

        DB::table('users')->updateOrInsert(
            ['email' => $email],
            $data
        );
    }
}
