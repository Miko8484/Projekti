<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Score;
use App\League;
use App\Match;
use App\Team;
use App\Bet;
use App\Standing;

class Sport extends Model
{

    protected $hidden = [
        'id'
    ];

    public function leagues(){
        $this->hasMany('League','sport_id','id');
    }

    /*public function scores(){
        //return $this->hasMany(Score::class,"user_id","id");
        $this->hasMany('Score','sport_id');
    }*/

    public function scores(){
        return $this->hasMany(Score::class);
    }

    public function matches(){
        return $this->hasMany(Match::class);
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
