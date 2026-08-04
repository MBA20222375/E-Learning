<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [];

        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'name'           => 'User ' . $i,
                'email'          => 'user' . $i . '@example.com',
                'password'       => Hash::make('password123'),
                'remember_token' => null,
                'role'           => ['admin', 'instructor', 'student'][array_rand(['admin', 'instructor', 'student'])],
                'image'          => null,
                'phone'          => '01000000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'experience'     => 'Experience of user ' . $i,
                'bio'            => 'This is the bio of user ' . $i,
                'address'        => 'Address ' . $i . ', Cairo, Egypt',
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
