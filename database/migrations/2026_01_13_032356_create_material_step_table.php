<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_steps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('material_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->integer('step_order');
            $table->string('title');
            $table->text('description');

            $table->string('image')->nullable();
            $table->string('video_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_steps');
    }
};
