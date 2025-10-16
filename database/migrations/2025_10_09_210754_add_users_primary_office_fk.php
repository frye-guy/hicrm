<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only add the FK if both table/column exist (safe in mixed environments)
        if (Schema::hasTable('users') && Schema::hasTable('offices') && Schema::hasColumn('users', 'primary_office_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Drop any existing FK silently would require try/catch; we just add if missing
                $table->foreign('primary_office_id')
                      ->references('id')
                      ->on('offices')
                      ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'primary_office_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['primary_office_id']);
            });
        }
    }
};
