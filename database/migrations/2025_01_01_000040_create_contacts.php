<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('lead_sources', function (Blueprint $t) {
$t->id();
$t->string('name')->unique();
$t->timestamps();
});
Schema::create('import_batches', function (Blueprint $t) {
$t->id();
$t->string('source_name');
$t->string('filename')->nullable();
$t->json('mapping')->nullable();
$t->timestamps();
});
Schema::create('contacts', function (Blueprint $t) {
$t->id();
$t->string('first_name');
$t->string('last_name');
$t->string('phone')->index();
$t->string('email')->nullable()->index();
$t->string('address')->nullable();
$t->string('city')->nullable();
$t->string('state')->nullable();
$t->string('zip')->nullable();
$t->foreignId('lead_source_id')->nullable()->constrained('lead_sources');
$t->foreignId('office_id')->nullable()->constrained('offices');
$t->json('interests')->nullable(); // [subproduct_id,...]
$t->json('tags')->nullable();
$t->boolean('consent_call')->default(true);
$t->unsignedSmallInteger('score')->default(0);
$t->foreignId('owner_id')->nullable()->constrained('users');
$t->foreignId('import_batch_id')->nullable()->constrained('import_batches');
$t->timestamps();
});
}
public function down(): void
{ Schema::dropIfExists('contacts'); Schema::dropIfExists('import_batches'); Schema::dropIfExists('lead_sources'); }
};