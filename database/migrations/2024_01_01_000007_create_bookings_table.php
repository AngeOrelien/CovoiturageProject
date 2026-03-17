<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ride_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('passenger_id')->constrained('users')->cascadeOnDelete();
            $table->integer('seats_booked')->default(1);
            $table->foreignUuid('pickup_location_id')->nullable()->constrained('locations');
            $table->foreignUuid('dropoff_location_id')->nullable()->constrained('locations');
            $table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bookings'); }
};
