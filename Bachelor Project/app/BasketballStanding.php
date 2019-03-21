<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sport;
use App\League;
use App\Team;
use App\Season;

class BasketballStanding extends Model
{
    public function sport(){
        return $this->belongsTo(Sport::class,'sport_id');
    }

    public function league(){
        return $this->belongsTo(League::class,'league_id');
    }

    public function team(){
        return $this->belongsTo(Team::class,'team_id','teamID');
    }

    public function season(){
        return $this->belongsTo(Season::class,'season_id');
    }
}
