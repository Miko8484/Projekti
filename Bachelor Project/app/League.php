<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Score;
use App\Sport;
use App\Match;
use App\Season;
use App\Team;
use App\Bet;
use App\Standing;

class League extends Model
{
    public function sport(){
        $this->belongsTo('Sport','sport_id','id');
    }

    public function scores(){
        $this->hasMany(Score::class,'league_id');;
    }

    public function matches(){
        return $this->hasMany(Match::class);
    }

    public function seasons(){
        return $this->hasMany(Season::class);
    }

    public function teams(){
        return $this->hasMany(Team::class);
    }

    public function bets(){
        return $this->hasMany(Bet::class);
    }

    public function standings(){
        return $this->hasMany(Standing::class);
    }
}
