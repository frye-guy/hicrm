<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('reset_by')->nullable()->constrained('users')->nullOnDelete()->after('set_by');
            $table->timestamp('reset_at')->nullable()->after('reset_by');
            $table->string('reset_reason', 255)->nullable()->after('reset_at');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reset_by');
            $table->dropColumn(['reset_at', 'reset_reason']);
        });
    }
};
