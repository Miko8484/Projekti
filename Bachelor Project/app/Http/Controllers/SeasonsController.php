<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Season;

class SeasonsController extends Controller
{
    public function show($league)
    {
        if($league=="NBA" || $league=="NHL")
        {
            $seasons = Season::whereHas('league', function($query) use($league) {
                $query->where('leagueName',$league);
            })->orderBy('start','DESC')->get();
            
            return json_encode($seasons);
        }
        else
        {
            $seasons = Season::whereHas('league', function($query) use($league) {
                                $query->where('leagueName',$league);
                            })->orderBy('start','DESC')->get();
            return json_encode($seasons);
        }
    }
}
