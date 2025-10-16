<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('queues', function (Blueprint $t) {
      $t->id(); $t->string('name');
      $t->foreignId('created_by')->constrained('users');
      $t->json('filters')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('queues'); }
};
