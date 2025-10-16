<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'lead_source_id')) {
                $table->foreignId('lead_source_id')
                      ->nullable()
                      ->constrained('lead_sources')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'lead_source_id')) {
                $table->dropForeign(['lead_source_id']);
                $table->dropColumn('lead_source_id');
            }
        });
    }
};
