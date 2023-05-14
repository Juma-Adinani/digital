<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no')->unique();
            $table->foreignId('program_id')->constrained('programmes');
            $table->foreignId('level_id')->constrained('education_levels');
            $table->enum('year_of_study', ['FIRST YEAR', 'SECOND YEAR', 'THIRD YEAR']);
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
