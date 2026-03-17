<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('wallet_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['credit','debit']);
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->string('reference_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('wallet_transactions'); }
};
