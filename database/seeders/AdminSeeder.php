<?php

namespace Database\Seeders;

use App\Enums\UserTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'ali',
            'last_name' => 'waked',
            'email' => 'ali.waked@gmail.com',
            'password' => Hash::make('password'),
            'type' => UserTypes::Admin,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
