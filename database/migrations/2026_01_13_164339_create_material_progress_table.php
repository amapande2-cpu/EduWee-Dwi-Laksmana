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
       Schema::create('material_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('material_id')->constrained()->cascadeOnDelete();

        $table->integer('completed_steps')->default(0);
        $table->integer('total_steps')->default(0);
        $table->integer('progress_percent')->default(0);
        $table->boolean('is_completed')->default(false);

        $table->timestamps();

        $table->unique(['student_id', 'material_id']);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_progress');
    }
};
