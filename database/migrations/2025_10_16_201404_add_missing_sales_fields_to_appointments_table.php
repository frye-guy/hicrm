<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $t) {
            // who/when
            if (!Schema::hasColumn('appointments','set_by_user_id'))        $t->foreignId('set_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            if (!Schema::hasColumn('appointments','set_with'))              $t->string('set_with')->nullable();                 // Both | Husband | Wife
            if (!Schema::hasColumn('appointments','confirmed_by_user_id'))  $t->foreignId('confirmed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            if (!Schema::hasColumn('appointments','confirmed_with'))        $t->string('confirmed_with')->nullable();

            // outcomes
            if (!Schema::hasColumn('appointments','result'))                $t->string('result')->nullable();
            if (!Schema::hasColumn('appointments','result_48hr'))           $t->boolean('result_48hr')->default(false);
            if (!Schema::hasColumn('appointments','result_onspot'))         $t->boolean('result_onspot')->default(false);
            if (!Schema::hasColumn('appointments','result_ran'))            $t->string('result_ran')->nullable();

            // sales / product
            if (!Schema::hasColumn('appointments','product_type'))          $t->string('product_type')->nullable();
            if (!Schema::hasColumn('appointments','amount_sold'))           $t->decimal('amount_sold', 12, 2)->nullable();
            if (!Schema::hasColumn('appointments','reset_by'))              $t->string('reset_by')->nullable();
            if (!Schema::hasColumn('appointments','issued_to'))             $t->string('issued_to')->nullable();
            if (!Schema::hasColumn('appointments','net'))                   $t->decimal('net', 12, 2)->nullable();
            if (!Schema::hasColumn('appointments','finance_result'))        $t->string('finance_result')->nullable();  // Finance | Cash
            if (!Schema::hasColumn('appointments','add_banks'))             $t->integer('add_banks')->nullable();

            // install / measure
            if (!Schema::hasColumn('appointments','install_at'))            $t->dateTime('install_at')->nullable();
            if (!Schema::hasColumn('appointments','installer'))             $t->string('installer')->nullable();
            if (!Schema::hasColumn('appointments','measured_by'))           $t->string('measured_by')->nullable();
            if (!Schema::hasColumn('appointments','measured_at'))           $t->dateTime('measured_at')->nullable();

            // windows/quantities
            if (!Schema::hasColumn('appointments','windows_qty'))           $t->integer('windows_qty')->nullable();
            if (!Schema::hasColumn('appointments','windows_replaced_qty'))  $t->integer('windows_replaced_qty')->nullable();

            // job status
            if (!Schema::hasColumn('appointments','job_status'))            $t->string('job_status')->nullable();

            // commissions / performance
            if (!Schema::hasColumn('appointments','commission_pct'))        $t->decimal('commission_pct', 5, 2)->nullable();
            if (!Schema::hasColumn('appointments','bonus_ovr'))             $t->decimal('bonus_ovr', 12, 2)->nullable();
            if (!Schema::hasColumn('appointments','below_par'))             $t->boolean('below_par')->default(false);
            if (!Schema::hasColumn('appointments','bonus_net'))             $t->decimal('bonus_net', 12, 2)->nullable();
            if (!Schema::hasColumn('appointments','par'))                   $t->decimal('par', 12, 2)->nullable();

            // measures
            if (!Schema::hasColumn('appointments','le_win'))                $t->decimal('le_win', 8, 2)->nullable();
            if (!Schema::hasColumn('appointments','tf_win'))                $t->decimal('tf_win', 8, 2)->nullable();
            if (!Schema::hasColumn('appointments','roof_sq'))               $t->integer('roof_sq')->nullable();
            if (!Schema::hasColumn('appointments','mat_sq'))                $t->integer('mat_sq')->nullable();
            if (!Schema::hasColumn('appointments','siding_sq'))             $t->integer('siding_sq')->nullable();
            if (!Schema::hasColumn('appointments','fascia_ft'))             $t->integer('fascia_ft')->nullable();
            if (!Schema::hasColumn('appointments','gutter_ft'))             $t->integer('gutter_ft')->nullable();

            // misc
            if (!Schema::hasColumn('appointments','appointment_source'))     $t->string('appointment_source')->nullable();
            if (!Schema::hasColumn('appointments','office'))                $t->string('office')->nullable();
            if (!Schema::hasColumn('appointments','credit_score'))          $t->unsignedSmallInteger('credit_score')->nullable();
            if (!Schema::hasColumn('appointments','got_docs'))              $t->boolean('got_docs')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $t) {
            // Keep down() simple & safe in prod: don't drop conditionally-added cols.
            // If you need a rollback, create a specific reverse migration.
        });
    }
};
