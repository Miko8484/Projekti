<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('game_id')->unsigned();
            $table->integer('sport_id')->unsigned();
            $table->integer('league_id')->unsigned();
            $table->integer('team1score')->nullable();
            $table->integer('team2score')->nullable();
            $table->integer('otherScore')->nullable();
            $table->string('status')->default('UNCHECKED');
            $table->timestamps();

            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade')->unsigned();
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade')->unsigned();
            $table->foreign('game_id')->references('gameID')->on('matches')->onDelete('cascade')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bets');
    }
}
