<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('dispositions') && ! Schema::hasColumn('dispositions', 'name')) {
            Schema::table('dispositions', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });

            // if you have an existing 'label' column, copy it into 'name'
            if (Schema::hasColumn('dispositions', 'label')) {
                DB::table('dispositions')->whereNull('name')->update([
                    'name' => DB::raw('label')
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('dispositions') && Schema::hasColumn('dispositions', 'name')) {
            Schema::table('dispositions', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};
