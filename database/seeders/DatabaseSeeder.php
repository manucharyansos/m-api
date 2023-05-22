<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        User::truncate();
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => '123456',
                'role' => 1,
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => '123456',
                'role' => 0,
            ],
            [
                'name' => 'Client',
                'email' => 'client@gmail.com',
                'password' => '123456',
                'role' => 0,
            ]
        ];
        foreach($users as $user)
        {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'role' => $user['role']
            ]);
        }
        Product::create([
            'image' => 'test',
            'category_id' => 1,
            'title' => 'Phone',
            'description' => 'description',
            'price' => 200
        ]);
    }
}
