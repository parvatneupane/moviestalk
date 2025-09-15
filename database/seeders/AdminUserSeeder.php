<?php

namespace Database\Seeders;
 use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
   

public function run()
{
    User::create([
        'name' => 'Super Admin',
        'email' => 'admin@domain.com',
        'password' => bcrypt('adminpassword'),
        'role' => 'admin',
    ]);
}

}
