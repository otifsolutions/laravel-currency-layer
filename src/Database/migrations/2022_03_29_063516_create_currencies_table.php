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
            $table->engine = 'MYISAM';
            $table->id();
            $table->string('currency');         // "AFN"
            $table->string('name');    // "Afghan Afghani"
            $table->string('symbol');  // "؋"
            $table->foreignId('country_id')->unique()->references('id')->on('countries');
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
