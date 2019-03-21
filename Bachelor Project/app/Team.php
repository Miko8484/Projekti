<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sport;
use App\League;
use App\Standing;
use App\Match;

class Team extends Model
{
    public function sport(){
        return $this->belongsTo(Sport::class,'sport_id');
    }

    public function league(){
        return $this->belongsTo(League::class,'league_id');
    }

    public function matches(){
        return $this->hasMany(Match::class);
    }

    public function standings(){
        return $this->hasMany(Standing::class);
    }
}
