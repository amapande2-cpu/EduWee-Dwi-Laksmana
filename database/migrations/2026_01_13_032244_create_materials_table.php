<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('class_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->text('description');

            $table->string('category');        // coding | ai | robotics
            $table->string('difficulty');      // beginner | intermediate | advanced
            $table->integer('duration')->nullable();

            $table->string('cover_image')->nullable();
            $table->string('quiz_url')->nullable();

            $table->boolean('is_public')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
