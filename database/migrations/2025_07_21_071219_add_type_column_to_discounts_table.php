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
        Schema::table('discounts', function (Blueprint $table) {
            $table->enum('type', ['fixed', 'ranged'])->after('name');
            $table->enum('discount_for', ['order', 'product'])->after('type');
            $table->decimal('discount_value', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn(['type', 'discount_for']);
            $table->decimal('discount_value', 10, 2);
        });
    }
};
