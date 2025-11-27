<?php
// database/migrations/xxxx_xx_xx_create_materials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // scratch or picto-ai
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->string('video_url');
            $table->integer('duration')->nullable(); // in minutes
            $table->string('difficulty')->default('beginner'); // beginner, intermediate, advanced
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};