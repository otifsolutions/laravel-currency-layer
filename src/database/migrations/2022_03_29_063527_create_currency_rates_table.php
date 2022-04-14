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
            $table->id();
            $table->double('usd_rates');    // usd to all other currencies
            $table->unsignedInteger('currency_id')
                ->index()
                ->nullable();

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies');

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