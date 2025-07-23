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
    Schema::table('discount_ranges', function (Blueprint $table) {
      $table->decimal('value', 10, 2)->after('max_price');
      $table->enum('type', ['percentage', 'amount'])->after('value');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('discount_ranges', function (Blueprint $table) {
      $table->dropColumn(['value', 'type']);
    });
  }
};
