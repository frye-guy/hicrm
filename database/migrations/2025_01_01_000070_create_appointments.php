<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('appointments', function (Blueprint $t) {
$t->id();
$t->foreignId('contact_id')->constrained();
$t->foreignId('sales_rep_id')->constrained('sales_reps');
$t->foreignId('subproduct_id')->nullable()->constrained('subproducts');
$t->timestamp('scheduled_for');
$t->unsignedInteger('duration_min')->default(60);
$t->foreignId('appointment_disposition_id')->nullable()->constrained('dispositions');
$t->boolean('is_sale')->default(false);
$t->foreignId('finance_disposition_id')->nullable()->constrained('dispositions');
$t->foreignId('job_status_disposition_id')->nullable()->constrained('dispositions');
$t->foreignId('set_by')->nullable()->constrained('users');
$t->json('meta')->nullable();
$t->timestamps();
$t->index(['sales_rep_id','scheduled_for']);
});
Schema::create('rep_availability', function (Blueprint $t) {
$t->id();
$t->foreignId('sales_rep_id')->constrained('sales_reps');
$t->date('date');
$t->time('start');
$t->time('end');
$t->timestamps();
$t->unique(['sales_rep_id','date','start','end']);
});
}
public function down(): void
{ Schema::dropIfExists('rep_availability'); Schema::dropIfExists('appointments'); }
};