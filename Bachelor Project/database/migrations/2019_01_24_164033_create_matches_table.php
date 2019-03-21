<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gameID')->unsigned();
            $table->integer('season_id')->unsigned();
            $table->integer('sport_id')->unsigned();
            $table->integer('league_id')->unsigned();
            $table->integer('team1_id')->unsigned();
            $table->integer('team2_id')->unsigned();
            $table->integer('team1goals')->nullable();
            $table->integer('team2goals')->nullable();
            $table->string('status');
            $table->string('winner')->nullable();
            $table->string('stage')->nullable();
            $table->string('groups')->nullable();
            $table->integer('matchday')->nullable();
            $table->datetime('startDate');
            $table->timestamps();

            $table->unique(array('league_id', 'gameID'));

            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade')->unsigned();
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade')->unsigned();
            $table->foreign('season_id')->references('seasonID')->on('seasons')->onDelete('cascade')->unsigned();
            $table->foreign('team1_id')->references('teamID')->on('teams')->onDelete('cascade')->unsigned();
            $table->foreign('team2_id')->references('teamID')->on('teams')->onDelete('cascade')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
