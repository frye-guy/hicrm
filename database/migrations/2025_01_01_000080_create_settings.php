public function up(): void
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('group')->default('app'); // e.g. 'app', 'google', 'ui'
        $table->string('key')->unique();         // e.g. 'google_maps_key'
        $table->text('value')->nullable();       // store string/json
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('settings');
}
