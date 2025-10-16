<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // when the appointment was actually set in the system
            if (!Schema::hasColumn('appointments', 'date_set')) {
                $table->timestamp('date_set')->nullable()->index();
            }

            // who set it / who with (store text labels; you can swap to FKs later)
            if (!Schema::hasColumn('appointments', 'set_by')) {
                $table->string('set_by', 120)->nullable();
            }
            if (!Schema::hasColumn('appointments', 'set_with')) {
                $table->string('set_with', 120)->nullable();
            }

            // sales metadata
            if (!Schema::hasColumn('appointments', 'interested_in')) {
                $table->string('interested_in', 120)->nullable();
            }
            if (!Schema::hasColumn('appointments', 'result')) {
                $table->string('result', 120)->nullable();
            }
            if (!Schema::hasColumn('appointments', 'notes')) {
                $table->longText('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            foreach (['date_set','set_by','set_with','interested_in','result','notes'] as $col) {
                if (Schema::hasColumn('appointments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
