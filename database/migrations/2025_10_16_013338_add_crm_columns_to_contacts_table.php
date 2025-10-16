<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Foreign key to lead_sources
            if (! Schema::hasColumn('contacts', 'lead_source_id')) {
                $table->foreignId('lead_source_id')
                    ->nullable()
                    ->constrained('lead_sources')
                    ->nullOnDelete()
                    ->after('zip'); // place it where you want
            }

            // Top line fields
            if (! Schema::hasColumn('contacts', 'dispo')) {
                $table->string('dispo')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('contacts', 'external_id')) {
                $table->string('external_id', 64)->nullable()->after('dispo');
            }
            if (! Schema::hasColumn('contacts', 'needs_reset')) {
                $table->boolean('needs_reset')->default(false)->after('external_id');
            }

            // Name
            if (! Schema::hasColumn('contacts', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('contacts', 'spouse')) {
                $table->string('spouse')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('contacts', 'last_name')) {
                $table->string('last_name')->nullable()->after('spouse');
            }

            // Email / Address
            if (! Schema::hasColumn('contacts', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('contacts', 'address')) {
                $table->string('address')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'city')) {
                $table->string('city')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'state')) {
                $table->string('state', 2)->nullable();
            }
            if (! Schema::hasColumn('contacts', 'zip')) {
                $table->string('zip', 10)->nullable();
            }

            // Work/Alt phones
            if (! Schema::hasColumn('contacts', 'mr_works')) {
                $table->string('mr_works')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'mrs_works')) {
                $table->string('mrs_works')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'alt_phone')) {
                $table->string('alt_phone', 25)->nullable();
            }
            if (! Schema::hasColumn('contacts', 'alt_phone2')) {
                $table->string('alt_phone2', 25)->nullable();
            }
            if (! Schema::hasColumn('contacts', 'alt_phone3')) {
                $table->string('alt_phone3', 25)->nullable();
            }

            // Search / Home details
            if (! Schema::hasColumn('contacts', 'search_tool')) {
                $table->string('search_tool')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'age_of_home')) {
                $table->string('age_of_home')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'home_type')) {
                $table->string('home_type')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'color_of_home')) {
                $table->string('color_of_home')->nullable();
            }
            if (! Schema::hasColumn('contacts', 'years_owned')) {
                $table->string('years_owned')->nullable();
            }

            // Geo / Zone
            if (! Schema::hasColumn('contacts', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable();
            }
            if (! Schema::hasColumn('contacts', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable();
            }
            if (! Schema::hasColumn('contacts', 'zone')) {
                $table->string('zone')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // drop in reverse order if you need to roll back
            if (Schema::hasColumn('contacts', 'zone')) $table->dropColumn('zone');
            if (Schema::hasColumn('contacts', 'lng')) $table->dropColumn('lng');
            if (Schema::hasColumn('contacts', 'lat')) $table->dropColumn('lat');
            if (Schema::hasColumn('contacts', 'years_owned')) $table->dropColumn('years_owned');
            if (Schema::hasColumn('contacts', 'color_of_home')) $table->dropColumn('color_of_home');
            if (Schema::hasColumn('contacts', 'home_type')) $table->dropColumn('home_type');
            if (Schema::hasColumn('contacts', 'age_of_home')) $table->dropColumn('age_of_home');
            if (Schema::hasColumn('contacts', 'search_tool')) $table->dropColumn('search_tool');
            if (Schema::hasColumn('contacts', 'alt_phone3')) $table->dropColumn('alt_phone3');
            if (Schema::hasColumn('contacts', 'alt_phone2')) $table->dropColumn('alt_phone2');
            if (Schema::hasColumn('contacts', 'alt_phone')) $table->dropColumn('alt_phone');
            if (Schema::hasColumn('contacts', 'mrs_works')) $table->dropColumn('mrs_works');
            if (Schema::hasColumn('contacts', 'mr_works')) $table->dropColumn('mr_works');

            if (Schema::hasColumn('contacts', 'zip')) $table->dropColumn('zip');
            if (Schema::hasColumn('contacts', 'state')) $table->dropColumn('state');
            if (Schema::hasColumn('contacts', 'city')) $table->dropColumn('city');
            if (Schema::hasColumn('contacts', 'address')) $table->dropColumn('address');
            if (Schema::hasColumn('contacts', 'email')) $table->dropColumn('email');

            if (Schema::hasColumn('contacts', 'last_name')) $table->dropColumn('last_name');
            if (Schema::hasColumn('contacts', 'spouse')) $table->dropColumn('spouse');
            if (Schema::hasColumn('contacts', 'first_name')) $table->dropColumn('first_name');

            if (Schema::hasColumn('contacts', 'needs_reset')) $table->dropColumn('needs_reset');
            if (Schema::hasColumn('contacts', 'external_id')) $table->dropColumn('external_id');
            if (Schema::hasColumn('contacts', 'dispo')) $table->dropColumn('dispo');

            if (Schema::hasColumn('contacts', 'lead_source_id')) {
                $table->dropConstrainedForeignId('lead_source_id');
            }
        });
    }
};
