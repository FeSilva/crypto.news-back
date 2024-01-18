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
        Schema::create('ticker_huobi', function (Blueprint $table) {
          $table->id();
          $table->string("currency")->index();
          $table->decimal("price", 19,9)->index();
          $table->string("change")->index();
          $table->string("percentage")->index();
          $table->string("volume")->index();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticker_huobi');
    }
};
