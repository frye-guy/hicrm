<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Core identity
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Auth
            $table->string('password');
            $table->rememberToken();

            // ? Added fields for your CRM
            $table->string('phone')->nullable()->index();

            // Keep as plain column + index for now (offices table is created later).
            // We'll add the actual foreign key in a separate migration after offices exist.
            $table->unsignedBigInteger('primary_office_id')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
