<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('number')->unique();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'completed', 'canceled', 'refunded'])->default('pending')->change();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->boolean('is_paid')->default(false);
            $table->char('currency', 3)->default('ILS');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
