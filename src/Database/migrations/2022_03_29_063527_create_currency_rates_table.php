<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->engine = 'myIsam';
            $table->id();
            $table->foreignId('currency_id')->references('id')->on('currencies');
            $table->string('baseCr', 10);   // the source
            $table->string('childCr', 10);  // the conversion
            $table->double('exchange_rates');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('currency_rates');
    }
};
