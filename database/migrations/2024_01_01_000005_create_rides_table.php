<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rides', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('origin_id')->constrained('locations');
            $table->foreignUuid('destination_id')->constrained('locations');
            $table->dateTime('departure_datetime');
            $table->dateTime('arrival_datetime')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('duration_min')->nullable();
            $table->integer('seats_available');
            $table->integer('seats_total');
            $table->decimal('price_per_seat', 10, 2);
            $table->enum('status', ['scheduled','active','completed','cancelled'])->default('scheduled');
            $table->text('description')->nullable();
            $table->boolean('is_recurrent')->default(false);
            $table->string('recurrence_rule')->nullable();
            $table->decimal('co2_saved_kg', 8, 2)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rides'); }
};
