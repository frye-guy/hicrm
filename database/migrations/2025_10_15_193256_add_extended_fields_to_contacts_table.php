<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // basic identifiers / status
            if (!Schema::hasColumn('contacts', 'external_id')) {
                $table->string('external_id', 50)->nullable()->index(); // UI: "ID"
            }
            if (!Schema::hasColumn('contacts', 'source')) {
                $table->string('source', 100)->nullable();             // UI: "Source"
            }
            if (!Schema::hasColumn('contacts', 'disposition')) {
                $table->string('disposition', 50)->nullable();         // UI: "Dispo"
            }
            if (!Schema::hasColumn('contacts', 'needs_reset')) {
                $table->boolean('needs_reset')->default(false);        // UI: "Needs Reset"
            }

            // names
            if (!Schema::hasColumn('contacts', 'spouse_name')) {
                $table->string('spouse_name', 150)->nullable();        // UI: "Spouse"
            }

            // phones
            if (!Schema::hasColumn('contacts', 'phone2')) {
                $table->string('phone2', 25)->nullable();              // UI: "Phone *2"
            }
            if (!Schema::hasColumn('contacts', 'alt_phone')) {
                $table->string('alt_phone', 25)->nullable();           // UI: "Alt Phone"
            }
            if (!Schema::hasColumn('contacts', 'alt_phone2')) {
                $table->string('alt_phone2', 25)->nullable();          // UI: "Alt Phone 2"
            }
            if (!Schema::hasColumn('contacts', 'alt_phone3')) {
                $table->string('alt_phone3', 25)->nullable();          // UI: "Alt Phone 3"
            }

            // address (guarded in case some already exist)
            if (!Schema::hasColumn('contacts', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'city')) {
                $table->string('city', 120)->nullable();
            }
            if (!Schema::hasColumn('contacts', 'state')) {
                $table->string('state', 60)->nullable();
            }
            if (!Schema::hasColumn('contacts', 'zip')) {
                $table->string('zip', 15)->nullable();
            }

            // household / property facts
            if (!Schema::hasColumn('contacts', 'mr_works')) {
                $table->string('mr_works', 50)->nullable();            // UI: "Mr Works"
            }
            if (!Schema::hasColumn('contacts', 'mrs_works')) {
                $table->string('mrs_works', 50)->nullable();           // UI: "Mrs Works"
            }
            if (!Schema::hasColumn('contacts', 'age_of_home')) {
                $table->string('age_of_home', 50)->nullable();         // UI: "Age of Home"
            }
            if (!Schema::hasColumn('contacts', 'type_of_home')) {
                $table->string('type_of_home', 50)->nullable();        // UI: "Type of Home"
            }
            if (!Schema::hasColumn('contacts', 'color_of_home')) {
                $table->string('color_of_home', 50)->nullable();       // UI: "Color of Home"
            }
            if (!Schema::hasColumn('contacts', 'years_owned')) {
                $table->string('years_owned', 120)->nullable();        // UI: "Years Owned"
            }
            if (!Schema::hasColumn('contacts', 'search_tools')) {
                $table->string('search_tools', 120)->nullable();       // UI: "Search Tools"
            }

            // geo helpers
            if (!Schema::hasColumn('contacts', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable();        // UI: "Lat"
            }
            if (!Schema::hasColumn('contacts', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable();       // UI: "Long"
            }
            if (!Schema::hasColumn('contacts', 'zone')) {
                $table->string('zone', 50)->nullable();                // UI: "Zone"
            }
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $drops = [
                'external_id','source','disposition','needs_reset',
                'spouse_name','phone2','alt_phone','alt_phone2','alt_phone3',
                'mr_works','mrs_works','age_of_home','type_of_home','color_of_home',
                'years_owned','search_tools','latitude','longitude','zone',
            ];

            foreach ($drops as $col) {
                if (Schema::hasColumn('contacts', $col)) {
                    $table->dropColumn($col);
                }
            }
            // We intentionally do NOT drop address/city/state/zip because other code may rely on them.
        });
    }
};
