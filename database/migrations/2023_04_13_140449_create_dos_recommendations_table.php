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
        Schema::create('dos_recommendations', function (Blueprint $table) {
            $table->id();
            $table->text('dos_remarks');
            $table->enum('status', [0, 1])->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('dof_remarks_id')->constrained('dof_recommendations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dos_recommendations');
    }
};
