<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Store raw image bytes in DB
            // Blueprint has `binary()` (BLOB). We'll upgrade to LONGBLOB via raw SQL for MySQL.
            $table->binary('profile_photo_blob')->nullable()->after('password');
            $table->string('profile_photo_mime', 100)->nullable()->after('profile_photo_blob');
        });

        // Ensure capacity for larger images on MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `teachers` MODIFY `profile_photo_blob` LONGBLOB NULL');
        }
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['profile_photo_blob', 'profile_photo_mime']);
        });
    }
};

