<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('offices', function (Blueprint $t) {
$t->id();
$t->string('name');
$t->string('phone')->nullable();
$t->string('address');
$t->decimal('lat', 10, 7)->nullable();
$t->decimal('lng', 10, 7)->nullable();
$t->timestamps();
});
Schema::create('sales_reps', function (Blueprint $t) {
$t->id();
$t->foreignId('user_id')->constrained();
$t->foreignId('office_id')->nullable()->constrained('offices');
$t->string('address')->nullable();
$t->decimal('lat', 10, 7)->nullable();
$t->decimal('lng', 10, 7)->nullable();
$t->unsignedInteger('radius_miles')->default(50);
$t->json('working_hours')->nullable(); // {Mon:["09:00-17:00"], ...}
$t->timestamps();
});
}
public function down(): void
{ Schema::dropIfExists('sales_reps'); Schema::dropIfExists('offices'); }
};