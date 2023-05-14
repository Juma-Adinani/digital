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
        Schema::create('dof_recommendations', function (Blueprint $table) {
            $table->id();
            $table->text('dof_remarks');
            $table->enum('status', [0, 1])->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('s_remark_id')->constrained('supervisor_recommendations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dof_recommendations');
    }
};
