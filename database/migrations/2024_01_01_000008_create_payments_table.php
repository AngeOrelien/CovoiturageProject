<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('payer_id')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('XAF');
            $table->enum('method', ['wallet','mobile_money','cash','card'])->default('wallet');
            $table->enum('status', ['pending','completed','failed','refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
