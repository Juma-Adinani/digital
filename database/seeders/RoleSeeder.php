<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {
        $roles = [
            ['role' => 'admin'],
            ['role' => 'student'],
            ['role' => 'class supervisor'],
            ['role' => 'dean of faculty'],
            ['role' => 'dean of school'],
            ['role' => 'deputy dean of school']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
