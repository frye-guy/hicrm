public function up(): void
{
    Schema::create('dispositions', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->boolean('requires_datetime')->default(false);
        $table->string('color', 20)->nullable(); // for UI tags/labels
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('dispositions');
}
