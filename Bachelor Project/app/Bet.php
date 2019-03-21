<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sport;
use App\League;
use App\Match;
use App\User;

class Bet extends Model
{
    public function sport(){
        return $this->belongsTo(Sport::class,'sport_id');
    }

    public function league(){
        return $this->belongsTo(League::class,'league_id');
    }

    public function match(){
        return $this->belongsTo(Match::class,'game_id','gameID');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
