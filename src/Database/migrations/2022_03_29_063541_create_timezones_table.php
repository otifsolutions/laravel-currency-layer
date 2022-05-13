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
        Schema::create('timezones', static function (Blueprint $table) {
            $table->engine = 'MYISAM';
            $table->id();
            $table->string('name');        // "Asia Kabul"
            $table->string('gmt_offset');       // 16200
            $table->string('gmt_offset_name');  // "UTC+04:30"
            $table->string('abbreviation');     // "AFT"
            $table->string('tz_name');          // "Afghanistan Time"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('timezones');
    }
};