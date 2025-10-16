<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('dispositions', function (Blueprint $t) {
$t->id();
$t->string('type'); // call|appointment|sale|finance|job
$t->string('code');
$t->string('label');
$t->boolean('active')->default(true);
$t->timestamps();
$t->unique(['type','code']);
});
Schema::create('calls', function (Blueprint $t) {
$t->id();
$t->foreignId('contact_id')->constrained();
$t->foreignId('user_id')->constrained('users');
$t->timestamp('started_at');
$t->timestamp('ended_at')->nullable();
$t->unsignedInteger('duration_sec')->default(0);
$t->foreignId('disposition_id')->nullable()->constrained('dispositions');
$t->text('notes')->nullable();
$t->timestamps();
$t->index(['user_id','started_at']);
});
Schema::create('current_calls', function (Blueprint $t) {
$t->id();
$t->foreignId('contact_id')->constrained();
$t->foreignId('user_id')->constrained();
$t->timestamp('since')->useCurrent();
$t->timestamps();
$t->unique(['contact_id']);
});
}
public function down(): void
{ Schema::dropIfExists('current_calls'); Schema::dropIfExists('calls'); Schema::dropIfExists('dispositions'); }
};