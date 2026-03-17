<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('vehicles')->update([
            'is_verified' => 1
        ]);
    }

    public function down(): void
    {
        DB::table('vehicles')->update([
            'is_verified' => 0
        ]);
    }
};
