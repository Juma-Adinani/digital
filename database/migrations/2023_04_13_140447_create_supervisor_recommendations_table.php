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
        Schema::create('supervisor_recommendations', function (Blueprint $table) {
            $table->id();
            $table->text('s_remarks');
            $table->enum('status', [0, 1])->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('leave_id')->constrained('student_leaves');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_recommendations');
    }
};
