<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sport_id')->unsigned();
            $table->integer('league_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('points');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->unsigned();
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
        Schema::dropIfExists('scores');
    }
}
