<?php
// database/migrations/2024_01_02_000004_update_materials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('class_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->after('class_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['class_id', 'teacher_id']);
        });
    }
};