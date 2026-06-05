<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('crypto_prices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('crypto_id')->constrained('cryptos')->onDelete('cascade');
        $table->decimal('price', 20, 8);
        $table->decimal('percent_change_24h', 10, 4)->nullable();
        $table->decimal('percent_change_7d', 10, 4)->nullable();
        $table->decimal('volume_24h', 30, 2)->nullable();
        $table->decimal('market_cap', 30, 2)->nullable();
        $table->timestamp('recorded_at');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_prices');
    }
};
