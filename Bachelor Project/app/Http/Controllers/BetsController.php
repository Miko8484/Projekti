<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Bet;
use App\User;

class BetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dt = Carbon::now();
        $user = User::where('username',$request->username)->first();
        $unsuccessfull=0;

        if($request->sport=="football" || $request->sport=="icehockey")
        {
            foreach($request->editMatches as $match)
            {
                if($match['startDate'] > $dt && $match['userTeam1Bet']!='' && $match['userTeam2Bet']!='')
                {
                    $betExsists=Bet::where('game_id',$match['gameID'])->where('user_id',$user->id)->first();
                    if($betExsists)
                    {
                        $betExsists->team1score=$match['userTeam1Bet'];
                        $betExsists->team2score=$match['userTeam2Bet'];
                        $betExsists->save();
                    }
                    else
                    {
                        $bet = new Bet();
                        $bet->user_id=$user->id;
                        $bet->game_id=$match['gameID'];
                        $bet->sport_id=$match['sport_id'];
                        $bet->league_id=$match['league_id'];
                        $bet->team1score=$match['userTeam1Bet'];
                        $bet->team2score=$match['userTeam2Bet'];
                        $bet->save();
                    }
                }
                else if($match['startDate'] < $dt)
                    $unsuccessfull+=1;
                else if((isset($match['userTeam1Bet']) && !isset($match['userTeam2Bet'])) || (!isset($match['userTeam1Bet']) && isset($match['userTeam2Bet'])))
                    $unsuccessfull+=1;
                else if(($match['userTeam1Bet']!='' && $match['userTeam2Bet']=='') || ($match['userTeam1Bet']=='' && $match['userTeam2Bet']!=''))
                    $unsuccessfull+=1;
            }
        }
        else
        {
            foreach($request->editMatches as $match)
            {
                if($match['startDate'] > $dt && ($match['otherScore']!='' || isset($match['pickedWinner'])) )
                {
                    $betExsists=Bet::where('game_id',$match['gameID'])->where('user_id',$user->id)->first();
                    if($betExsists)
                    {
                        if(isset($match['otherScore']) && $match['otherScore']!='')
                            $betExsists->otherScore=$match['otherScore'];
                        if(isset($match['pickedWinner']))
                        {
                            if($match['pickedWinner']=='HOME_TEAM')
                            {
                                $betExsists->team1score=1;
                                $betExsists->team2score=0;
                            }
                            else
                            {
                                $betExsists->team1score=0;
                                $betExsists->team2score=1;
                            }
                        }
                        $betExsists->save();
                    }
                    else
                    {
                        $bet = new Bet();
                        $bet->user_id=$user->id;
                        $bet->game_id=$match['gameID'];
                        $bet->sport_id=$match['sport_id'];
                        $bet->league_id=$match['league_id'];
                        if(isset($match['otherScore']) && $match['otherScore']!='')
                        $bet->otherScore=$match['otherScore'];
                        if(isset($match['pickedWinner']))
                        {
                            if($match['pickedWinner']=='HOME_TEAM')
                            {
                                $bet->team1score=1;
                                $bet->team2score=0;
                            }
                            else
                            {
                                $bet->team1score=0;
                                $bet->team2score=1;
                            }
                        }
                        $bet->save();
                    }
                }
                else
                    $unsuccessfull+=1;
            }
        }

        if($unsuccessfull==0)
            return "You placed your bets!";
        else
            return $unsuccessfull." bets were unsuccessfully placed due to placing them to late or not providing goals for both teams";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
