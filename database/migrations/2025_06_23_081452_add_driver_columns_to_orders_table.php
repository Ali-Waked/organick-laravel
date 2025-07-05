<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('user_id');
            $table->foreignId('assigned_by_id')->nullable()->constrained('users')->nullOnDelete()->after('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['assigned_by_id']);
            $table->dropColumn(['driver_id', 'assigned_by_id']);
        });
    }
};
