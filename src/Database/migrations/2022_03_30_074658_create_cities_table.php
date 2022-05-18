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
        Schema::create('cities', function (Blueprint $table) {
            $table->engine = 'myIsam';
            $table->id();
            $table->string('name');         //  cityName = "ashkasham"
            $table->string('state_code');
            $table->float('latitude');     // like "36.68333000"
            $table->float('longitude');    // like "71.53333000"
            $table->string('wiki_data_id');   // like "Q4805192"
            $table->foreignId('state_id')->references('id')->on('state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('cities');
    }
};
