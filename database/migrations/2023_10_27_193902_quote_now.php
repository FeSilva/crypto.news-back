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
      Schema::create('quote_now', function (Blueprint $table) {
        $table->id();
        $table->string("exchange")->index()->notnull();
        $table->string("currency")->index()->notnull();
        $table->string("quote_currency")->index()->notnull();
        $table->string("change")->index()->notnull();
        $table->string("percent")->index()->notnull();
        $table->decimal("price",19,9)->index()->notnull();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('quote_now');
    }
};
