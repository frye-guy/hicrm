<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lead_sources', function (Blueprint $table) {
            if (! Schema::hasColumn('lead_sources', 'short_code')) {
                $table->string('short_code', 50)->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('lead_sources', 'description')) {
                $table->text('description')->nullable()->after('short_code');
            }
            if (! Schema::hasColumn('lead_sources', 'contact_name')) {
                $table->string('contact_name')->nullable()->after('description');
            }
            if (! Schema::hasColumn('lead_sources', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('contact_name');
            }
            if (! Schema::hasColumn('lead_sources', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
            if (! Schema::hasColumn('lead_sources', 'type_id')) {
                $table->foreignId('type_id')->nullable()->constrained('lead_source_types')->nullOnDelete()->after('contact_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lead_sources', function (Blueprint $table) {
            if (Schema::hasColumn('lead_sources', 'type_id')) {
                $table->dropConstrainedForeignId('type_id');
            }
            foreach (['short_code','description','contact_name','contact_email','contact_phone'] as $col) {
                if (Schema::hasColumn('lead_sources', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
