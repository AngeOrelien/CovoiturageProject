<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('reporter_id')->constrained('users');
            $table->foreignUuid('reported_user_id')->constrained('users');
            $table->foreignUuid('ride_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reason');
            $table->text('description')->nullable();
            $table->enum('status', ['pending','reviewed','resolved','dismissed'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('reports'); }
};
