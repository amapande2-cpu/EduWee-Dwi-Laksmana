<?php
// database/migrations/2024_01_02_000003_create_class_student_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // student
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            // Prevent duplicate enrollments
            $table->unique(['class_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};