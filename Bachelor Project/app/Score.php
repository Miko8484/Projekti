<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Sport;
use App\League;

class Score extends Model
{
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function sport(){
        return $this->belongsTo(Sport::class,'sport_id');
    }

    public function league(){
        return $this->belongsTo(League::class,'league_id');
    }
}