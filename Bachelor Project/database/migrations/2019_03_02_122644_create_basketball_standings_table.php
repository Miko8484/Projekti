<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasketballStandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basketball_standings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sport_id')->unsigned();
            $table->integer('league_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->integer('season_id')->unsigned();
            $table->integer('place');
            $table->integer('gamesPlayed');
            $table->decimal('winPrct');
            $table->decimal('gamesBehind');
            $table->integer('won');
            $table->integer('lost');
            $table->integer('streak');
            $table->string('group')->nullable();
            $table->timestamps();

            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade')->unsigned();
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade')->unsigned();
            $table->foreign('season_id')->references('seasonID')->on('seasons')->onDelete('cascade')->unsigned();
            $table->foreign('team_id')->references('teamID')->on('teams')->onDelete('cascade')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basketball_standings');
    }
}
