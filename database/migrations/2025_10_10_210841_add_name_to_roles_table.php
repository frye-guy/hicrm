<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('roles') && ! Schema::hasColumn('roles','name')) {
            Schema::table('roles', function (Blueprint $t) {
                $t->string('name')->unique()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles') && Schema::hasColumn('roles','name')) {
            Schema::table('roles', function (Blueprint $t) {
                $t->dropUnique(['name']);
                $t->dropColumn('name');
            });
        }
    }
};
