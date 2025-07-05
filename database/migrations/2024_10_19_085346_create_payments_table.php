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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->morphs('paymentable');
            $table->decimal('total_price', 10, 2);
            $table->char('currency', 3);
            $table->foreignId('payment_method_id')->constrained();
            $table->enum('status', ['pending', 'completed', 'failed', 'canceled'])->default('pending');
            $table->text('transaction_id')->nullable();
            $table->json('transaction_data')->nullable();
            $table->timestamps();
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
