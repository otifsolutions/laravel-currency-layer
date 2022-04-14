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
        Schema::create('currencies', static function (Blueprint $table) {
            $table->id();
            $table->string('currency');         // "AFN"
            $table->string('currency_name');    // "Afghan Afghani"
            $table->string('currency_symbol');  // "؋"

            $table->integer('country_id')
                ->unsigned()
                ->index()
                ->nullable();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('currencies');
    }
};
