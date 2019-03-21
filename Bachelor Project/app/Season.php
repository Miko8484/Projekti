<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
Use App\Match;
Use App\League;
use App\Standing;

class Season extends Model
{
    public $timestamps = false;

    public function matches(){
        $this->hasMany(Match::class,'season_id');;
    }

    public function league(){
        return $this->belongsTo(League::class,'league_id');
    }

    public function standings(){
        $this->hasMany(Standing::class,'season_id');;
    }
}
