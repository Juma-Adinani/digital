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
        Schema::create('reasons_for_leave', function (Blueprint $table) {
            $table->id();
            $table->text('medical_reason')->nullable();
            $table->text('social_reason')->nullable();
            $table->text('attachment')->nullable();
            $table->foreignId('reason_type_id')->constrained('reason_types');
            $table->foreignId('student_id')->constrained('students');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reasons_for_leave');
    }
};
