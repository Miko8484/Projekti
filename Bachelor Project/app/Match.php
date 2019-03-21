<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sport;
use App\League;
use App\Season;

class Match extends Model
{
    protected $hidden = [
        'id', 'season_id', 'team1_id', 'team2_id', 'status', 'created_at', 'updated_at'
    ];

    public function sport(){
        return $this->belongsTo(Sport::class,'sport_id');
    }

    public function league(){
        return $this->belongsTo(League::class,'league_id');
    }

    public function season(){
        return $this->belongsTo(Season::class,'season_id');
    }

    public function team1(){
        return $this->belongsTo(Team::class,'team1_id','teamID');
    }

    public function team2(){
        return $this->belongsTo(Team::class,'team2_id','teamID');
    }

    public function bets(){
        return $this->hasMany(Bet::class);
    }
}
