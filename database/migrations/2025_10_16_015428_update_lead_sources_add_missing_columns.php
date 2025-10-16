<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_sources', function (Blueprint $table) {
            if (! Schema::hasColumn('lead_sources', 'short_code')) {
                $table->string('short_code')->nullable()->after('name');
            }
            if (! Schema::hasColumn('lead_sources', 'type')) {
                $table->string('type')->nullable()->after('short_code');
            }
            if (! Schema::hasColumn('lead_sources', 'description')) {
                $table->string('description')->nullable()->after('type');
            }
            if (! Schema::hasColumn('lead_sources', 'active')) {
                $table->boolean('active')->default(true)->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lead_sources', function (Blueprint $table) {
            if (Schema::hasColumn('lead_sources', 'short_code')) $table->dropColumn('short_code');
            if (Schema::hasColumn('lead_sources', 'type')) $table->dropColumn('type');
            if (Schema::hasColumn('lead_sources', 'description')) $table->dropColumn('description');
            if (Schema::hasColumn('lead_sources', 'active')) $table->dropColumn('active');
        });
    }
};
