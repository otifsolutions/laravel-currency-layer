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
            $table->id();
            $table->string('name');         //  cityName = "ashkasham"
            $table->string('state_code');   // like "BDS"
            $table->string('state_name');   // like "badakhshan"
            $table->string('country_code'); // like "AF"
            $table->string('country_name'); // like "afghanistah"
            $table->float('latitude');     // like "36.68333000"
            $table->float('longitude');    // like "71.53333000"
            $table->string('wikiDataId');   // like "Q4805192"

            $table->integer('state_id')
                ->unsigned()
                ->index()
                ->nullable();

            $table->foreign('state_id')
                ->references('id')
                ->on('states');

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
