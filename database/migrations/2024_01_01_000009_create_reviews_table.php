<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('reviewer_id')->constrained('users');
            $table->foreignUuid('reviewee_id')->constrained('users');
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();
            $table->enum('type', ['passenger_to_driver','driver_to_passenger']);
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('reviews'); }
};
