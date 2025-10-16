<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('confirmation_results', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('result_rans', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('confirmation_results');
        Schema::dropIfExists('result_rans');
    }
};
