<?php

namespace Database\Seeders;

use App\Models\SessionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SessionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {
        $sessionTypes = [
            ['type' => 'Period session'],
            ['type' => 'Tutorial session'],
            ['type' => 'Assignment'],
            ['type' => 'Test'],
            ['type' => 'Examination']
        ];

        foreach ($sessionTypes as $sessionType) {
            SessionType::create($sessionType);
        }
    }
}
