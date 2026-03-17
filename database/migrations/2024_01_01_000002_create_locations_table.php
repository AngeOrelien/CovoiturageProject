<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('label');
            $table->string('city');
            $table->string('country')->default('Cameroun');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('place_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('locations'); }
};
