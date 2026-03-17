<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('driver_id')->constrained('driver_profiles')->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->string('color');
            $table->string('license_plate')->unique();
            $table->integer('nb_seats');
            $table->enum('fuel_type', ['essence', 'diesel', 'hybride', 'electrique'])->default('essence');
            $table->boolean('is_verified')->default(false);
            $table->json('photos')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vehicles'); }
};
