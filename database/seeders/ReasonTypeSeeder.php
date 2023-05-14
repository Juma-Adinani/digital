<?php

namespace Database\Seeders;

use App\Models\ReasonType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {
        $reasonTypes = [
            ['type' => 'medical reasons'],
            ['type' => 'social reasons'],
            ['type' => 'both']
        ];

        foreach ($reasonTypes as $reasonType) {
            ReasonType::create($reasonType);
        }
    }
}
