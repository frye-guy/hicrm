<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('user_sessions', function (Blueprint $t) {
$t->id();
$t->foreignId('user_id')->constrained();
$t->timestamp('logged_in_at');
$t->timestamp('last_activity_at');
$t->timestamp('logged_out_at')->nullable();
$t->unsignedInteger('active_seconds')->default(0);
$t->timestamps();
$t->index(['user_id','last_activity_at']);
});
}
public function down(): void { Schema::dropIfExists('user_sessions'); }
};