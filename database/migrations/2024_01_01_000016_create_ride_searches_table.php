<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ride_searches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('origin_id')->constrained('locations');
            $table->foreignUuid('destination_id')->constrained('locations');
            $table->date('desired_date');
            $table->integer('seats_needed')->default(1);
            $table->decimal('max_price', 10, 2)->nullable();
            $table->boolean('is_alert_active')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('ride_searches'); }
};
