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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // state name like "badakhshan"
            $table->string('country_code');     // country code like "AF" for afghanistan
            $table->string('country_name');     // country name like "Afghanistan"
            $table->string('state_code');       // state code like "BDS" for badakhshan
            $table->float('latitude');
            $table->float('longitude');
            $table->integer('country_id')->unsigned()->index()->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('states');
    }
};
