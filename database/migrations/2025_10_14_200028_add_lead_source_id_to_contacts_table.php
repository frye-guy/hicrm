<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add the column if missing
        if (! Schema::hasColumn('contacts', 'lead_source_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->foreignId('lead_source_id')
                    ->nullable()
                    ->after('state');
            });
        }

        // 2) Add the FK only if it doesn't already exist
        $constraintName = 'contacts_lead_source_id_foreign';

        if (! $this->foreignKeyExists('contacts', $constraintName)
            && ! $this->foreignKeyOnColumnExists('contacts', 'lead_source_id')) {

            Schema::table('contacts', function (Blueprint $table) use ($constraintName) {
                $table->foreign('lead_source_id', $constraintName)
                    ->references('id')
                    ->on('lead_sources')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Drop FK if present
        $constraintName = 'contacts_lead_source_id_foreign';

        if ($this->foreignKeyExists('contacts', $constraintName)) {
            Schema::table('contacts', function (Blueprint $table) use ($constraintName) {
                $table->dropForeign($constraintName);
            });
        } elseif ($this->foreignKeyOnColumnExists('contacts', 'lead_source_id')) {
            // fallback if FK exists under a different generated name
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropForeign(['lead_source_id']);
            });
        }

        // Drop column if present
        if (Schema::hasColumn('contacts', 'lead_source_id')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('lead_source_id');
            });
        }
    }

    /**
     * Returns true if a FK with this exact name exists on the table.
     */
    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $sql = <<<SQL
SELECT 1
FROM information_schema.TABLE_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = DATABASE()
  AND TABLE_NAME = ?
  AND CONSTRAINT_NAME = ?
  AND CONSTRAINT_TYPE = 'FOREIGN KEY'
LIMIT 1
SQL;

        return (bool) DB::selectOne($sql, [$table, $constraintName]);
    }

    /**
     * Returns true if any FK exists on the given column (regardless of name).
     */
    private function foreignKeyOnColumnExists(string $table, string $column): bool
    {
        $sql = <<<SQL
SELECT 1
FROM information_schema.KEY_COLUMN_USAGE
WHERE CONSTRAINT_SCHEMA = DATABASE()
  AND TABLE_NAME = ?
  AND COLUMN_NAME = ?
  AND REFERENCED_TABLE_NAME IS NOT NULL
LIMIT 1
SQL;

        return (bool) DB::selectOne($sql, [$table, $column]);
    }
};
