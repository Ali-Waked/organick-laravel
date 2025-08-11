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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('name');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->text('reply_message')->nullable();
            // $table->timestamp('read_at')->nullable();
            $table->timestamp('replyed_at')->nullable();
            $table->foreignId('reply_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};