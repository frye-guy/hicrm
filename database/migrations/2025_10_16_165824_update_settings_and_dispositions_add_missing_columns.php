<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SETTINGS table: add commonly-missed keys safely (only if they don't exist)
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'google_maps_api_key')) {
                    $table->string('google_maps_api_key')->nullable()->after('id');
                }
                if (!Schema::hasColumn('settings', 'google_distance_matrix_api_key')) {
                    $table->string('google_distance_matrix_api_key')->nullable()->after('google_maps_api_key');
                }
                if (!Schema::hasColumn('settings', 'recaptcha_site_key')) {
                    $table->string('recaptcha_site_key')->nullable();
                }
                if (!Schema::hasColumn('settings', 'recaptcha_secret_key')) {
                    $table->string('recaptcha_secret_key')->nullable();
                }
                if (!Schema::hasColumn('settings', 'ui_primary_color')) {
                    $table->string('ui_primary_color', 32)->nullable();
                }
                if (!Schema::hasColumn('settings', 'ui_secondary_color')) {
                    $table->string('ui_secondary_color', 32)->nullable();
                }
            });
        }

        // DISPOSITIONS table: add a few useful columns safely
        if (Schema::hasTable('dispositions')) {
            Schema::table('dispositions', function (Blueprint $table) {
                if (!Schema::hasColumn('dispositions', 'slug')) {
                    $table->string('slug')->nullable()->unique();
                }
                if (!Schema::hasColumn('dispositions', 'is_positive')) {
                    $table->boolean('is_positive')->default(false);
                }
                if (!Schema::hasColumn('dispositions', 'is_final')) {
                    $table->boolean('is_final')->default(false);
                }
                if (!Schema::hasColumn('dispositions', 'row_color')) {
                    $table->string('row_color', 16)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        // Reverse the "safe adds" conservatively
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (Schema::hasColumn('settings', 'google_maps_api_key')) {
                    $table->dropColumn('google_maps_api_key');
                }
                if (Schema::hasColumn('settings', 'google_distance_matrix_api_key')) {
                    $table->dropColumn('google_distance_matrix_api_key');
                }
                if (Schema::hasColumn('settings', 'recaptcha_site_key')) {
                    $table->dropColumn('recaptcha_site_key');
                }
                if (Schema::hasColumn('settings', 'recaptcha_secret_key')) {
                    $table->dropColumn('recaptcha_secret_key');
                }
                if (Schema::hasColumn('settings', 'ui_primary_color')) {
                    $table->dropColumn('ui_primary_color');
                }
                if (Schema::hasColumn('settings', 'ui_secondary_color')) {
                    $table->dropColumn('ui_secondary_color');
                }
            });
        }

        if (Schema::hasTable('dispositions')) {
            Schema::table('dispositions', function (Blueprint $table) {
                if (Schema::hasColumn('dispositions', 'slug')) {
                    $table->dropUnique(['slug']);
                    $table->dropColumn('slug');
                }
                if (Schema::hasColumn('dispositions', 'is_positive')) {
                    $table->dropColumn('is_positive');
                }
                if (Schema::hasColumn('dispositions', 'is_final')) {
                    $table->dropColumn('is_final');
                }
                if (Schema::hasColumn('dispositions', 'row_color')) {
                    $table->dropColumn('row_color');
                }
            });
        }
    }
};
