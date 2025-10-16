<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('products', function (Blueprint $t) {
$t->id();
$t->string('name');
$t->timestamps();
});
Schema::create('subproducts', function (Blueprint $t) {
$t->id();
$t->foreignId('product_id')->constrained();
$t->string('name');
$t->text('description')->nullable();
$t->timestamps();
});
Schema::create('pricing', function (Blueprint $t) {
$t->id();
$t->foreignId('subproduct_id')->constrained();
$t->decimal('base_price', 10, 2);
$t->json('addons')->nullable();
$t->json('finance_options')->nullable();
$t->timestamps();
});
Schema::create('rep_specializations', function (Blueprint $t) {
$t->id();
$t->foreignId('sales_rep_id')->constrained();
$t->foreignId('subproduct_id')->constrained();
$t->timestamps();
$t->unique(['sales_rep_id','subproduct_id']);
});
}
public function down(): void
{ Schema::dropIfExists('rep_specializations'); Schema::dropIfExists('pricing'); Schema::dropIfExists('subproducts'); Schema::dropIfExists('products'); }
};