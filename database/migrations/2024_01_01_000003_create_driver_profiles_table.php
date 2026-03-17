<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('driver_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->boolean('is_license_verified')->default(false);
            $table->integer('years_of_experience')->default(0);
            $table->json('preferences')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('driver_profiles'); }
};
