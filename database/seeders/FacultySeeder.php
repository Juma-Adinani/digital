<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {
        $faculties = [
            ['faculty_code' => 'FST', 'faculty_name' => 'FACULTY OF SCIENCE AND TECHNOLOGY'],
            ['faculty_code' => 'FSS', 'faculty_name' => 'FACULTY OF SOCIAL SCIENCE'],
            ['faculty_code' => 'SOB', 'faculty_name' => 'SCHOOL OF BUSINESS'],
            ['faculty_code' => 'FOL', 'faculty_name' => 'FACULTY OF LAW'],
            [
                'faculty_code' => 'SOPAM', 'faculty_name' => 'SCHOOL OF PUBLIC ADMINISTRATION AND MANAGEMENT'
            ]
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
