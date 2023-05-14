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
        Schema::create('away_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_type_id')->constrained('session_types');
            $table->string('subject');
            $table->string('lecturer');
            $table->string('commence_date');
            $table->foreignId('reason_id')->constrained('reasons_for_leave');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('away_sessions');
    }
};
