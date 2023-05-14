<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {
        User::create(
            [
                'firstname' => 'Tumaini',
                'middlename' => 'Michael',
                'lastname' => 'Kivamba',
                'gender' => 'FEMALE',
                'email' => 'tumaini@digitalpermission.com',
                'phone' => '+255677600075',
                'password' => Hash::make('admin123'),
                'role_id' => 1
            ]
        );

        User::create([
            'firstname' => 'Ally',
            'middlename' => 'Salehe',
            'lastname' => 'Jacob',
            'gender' => 'MALE',
            'email' => 'samsonj@digitalpermission.com',
            'phone' => '+255677607875',
            'password' => Hash::make('schooldean123'),
            'role_id' => 5
        ]);

        User::create([
            'firstname' => 'Maria',
            'middlename' => 'Elias',
            'lastname' => 'Mariki',
            'gender' => 'FEMALE',
            'email' => 'mariae@digitalpermission.com',
            'phone' => '+255757007195',
            'password' => Hash::make('deputydean123'),
            'role_id' => 6
        ]);
    }
}
