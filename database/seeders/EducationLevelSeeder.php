<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {
        $educationLevels = [
            ['level' => 'CERTIFICATE'],
            ['level' => 'DIPLOMA'],
            ['level' => 'DEGREE'],
            ['level' => 'MASTERS'],
            ['level' => 'PhD']
        ];

        foreach ($educationLevels as $educationLevel) {
            EducationLevel::create($educationLevel);
        }
    }
}
