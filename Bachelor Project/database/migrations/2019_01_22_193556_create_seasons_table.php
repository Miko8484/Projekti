<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seasonID')->unsigned()->unique();
            $table->integer('sport_id')->unsigned();
            $table->integer('league_id')->unsigned();
            $table->integer('currentMatchday')->nullable();
            $table->date('start');
            $table->date('end');

            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade')->unsigned();
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
}
