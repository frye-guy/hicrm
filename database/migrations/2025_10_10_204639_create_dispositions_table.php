<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('dispositions')) {
            Schema::create('dispositions', function (Blueprint $t) {
                $t->id();
                $t->string('type');   // call | appointment | sale | finance | job
                $t->string('code');
                $t->string('label');
                $t->boolean('active')->default(true);
                $t->timestamps();
                $t->unique(['type','code']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};
