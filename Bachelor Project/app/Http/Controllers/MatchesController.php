<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;

use App\Match;
use App\Season;
use App\League;
use App\Team;
use App\Bet;
use App\Score;
use App\BasketballStanding;
use App\FootballStanding;
use Football;

class MatchesController extends Controller
{
    public function getIceHockeySeasons()
    {
        $client = new Client();
        $result = $client->get('https://statsapi.web.nhl.com/api/v1/schedule');
        $data=json_decode($result->getBody());

        $seasonID=$data->dates[0]->games[0]->season;
        $seasonExsists=Season::where('league_id',7)->where('seasonID',$seasonID)->first();
        if(!$seasonExsists)
        {
            $season = new Season();
            $season->seasonID=$seasonID;
            $season->sport_id=3;
            $season->league_id=7;
            $season->start=Carbon::create(substr($seasonID,0,4), 1, 1, 0)->toDateTimeString();
            $season->end=Carbon::create(substr($seasonID,4,4), 1, 1, 0)->toDateTimeString();
            $season->save();
        }
    }

    public function getIceHockeyTeams()
    {
        $client = new Client();
        $result = $client->get('https://statsapi.web.nhl.com/api/v1/teams');
        $data=json_decode($result->getBody());

        foreach($data->teams as $team)
        {
            $teamExists=Team::where('teamID',$team->id)->where('league_id',7)->get();
            if($teamExists->isEmpty())
            {
                $t=new Team();
                $t->sport_id=3;
                $t->league_id=7;
                $t->teamID=$team->id;
                $t->name=$team->name;
                $t->shortName=$team->teamName;
                $t->save();
            }
        }
    }

    public function getIceHockeyMatches()
    {
        $seasons=Season::where('league_id',7)->get();
        $currentSeason=$seasons->last();
        
        $startYear=substr($currentSeason->seasonID,0,4);
        $endYear=substr($currentSeason->seasonID,0,4);

        $client = new Client();
        $result = $client->get('https://statsapi.web.nhl.com/api/v1/schedule?startDate='.$startYear.'-09-25&endDate='.$endYear.'-07-01');
        $data=json_decode($result->getBody());

        foreach($data->dates as $dayGames)
        {
            foreach($dayGames->games as $game)
            {
                if($game->gameType=="R" || $game->gameType=="P")
                {
                    $match=new Match();
                    $match->sport_id=3;
                    $match->league_id=7;
                    $match->season_id=$currentSeason->seasonID;
                    $match->gameID=$game->gamePk;
                    $match->team1_id=$game->teams->home->team->id;
                    $match->team2_id=$game->teams->away->team->id;

                    if($game->status->detailedState=="Final")
                        $match->status="FINISHED";
                    else if($game->status->detailedState=="Scheduled")
                        $match->status="Scheduled";
                    else
                        $match->status=$game->status->detailedState;

                    if($game->teams->home->score!=0 || $game->teams->away->score!=0)
                    {
                        $match->team1goals=$game->teams->home->score;
                        $match->team2goals=$game->teams->away->score;

                        if($game->teams->home->score > $game->teams->away->score)
                            $match->winner="HOME_TEAM";
                        else
                            $match->winner="AWAY_TEAM";
                    }

                    if($game->gameType=="R")
                        $match->stage="Regular";
                    else if($game->gameType=="P")
                        $match->stage="Playoff";
                    else
                        $match->stage=$game->gameType;

                    $match->startDate=$game->gameDate;

                    $match->save();
                }
            }
        }
    }

    public function updateIceHockeyMatches()
    {
        $seasons=Season::where('league_id',7)->get();
        $currentSeason=$seasons->last();

        $matchesExists=Match::where('league_id',7)->where('season_id',$currentSeason->seasonID)->first();

        if($matchesExists)
        {
            $startDate=Carbon::today()->subWeek()->toDateString();
            $endDate=Carbon::today()->addWeek()->toDateString();

            $client = new Client();
            $result = $client->get('https://statsapi.web.nhl.com/api/v1/schedule?startDate='.$startDate.'&endDate='.$endDate);
            $data=json_decode($result->getBody());
            
            foreach($data->dates as $dayGames)
            {
                foreach($dayGames->games as $game)
                {
                    $matchExists=Match::where('gameID',$game->gamePk)->where('league_id',7)->first();
                    if($matchExists)
                    {
                        $matchExists->team1goals=$game->teams->home->score;
                        $matchExists->team2goals=$game->teams->away->score;
                        if($game->status->detailedState=="Final")
                            $matchExists->status="FINISHED";
                        else if($game->status->detailedState=="Scheduled")
                            $matchExists->status="SCHEDULED";
                        else
                            $matchExists->status=$game->status->detailedState;

                        if($game->teams->home->score!=0 || $game->teams->away->score!=0)
                        {
                            if($game->teams->home->score > $game->teams->away->score)
                                $matchExists->winner="HOME_TEAM";
                            else
                                $matchExists->winner="AWAY_TEAM";
                        }

                        $matchExists->save();
                    }
                    else
                    {
                        $match=new Match();
                        $match->sport_id=3;
                        $match->league_id=7;
                        $match->season_id=$currentSeason->seasonID;
                        $match->gameID=$game->gamePk;
                        $match->team1_id=$game->teams->home->team->id;
                        $match->team2_id=$game->teams->away->team->id;
                        if($game->status->detailedState=="Final")
                            $match->status="FINISHED";
                        else if($game->status->detailedState=="Scheduled")
                            $match->status="Scheduled";
                        else
                            $match->status=$game->status->detailedState;

                        if($game->teams->home->score!=0 || $game->teams->away->score!=0)
                        {
                            $match->team1goals=$game->teams->home->score;
                            $match->team2goals=$game->teams->away->score;
                            if($game->teams->home->score > $game->teams->away->score)
                                $match->winner="HOME_TEAM";
                            else
                                $match->winner="AWAY_TEAM";
                        }

                        if($game->gameType=="R")
                            $match->stage="Regular";
                        else if($game->gameType=="P")
                            $match->stage="Playoff";
                        else
                            $match->stage=$game->gameType;

                        $match->startDate=$game->gameDate;

                        $match->save();
                    }
                }
            }
        }
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }

    public function showIcehockeyMatches($request,$league)
    {   
        $forwardDate=Carbon::today()->addDays(3);
        $yesterday=Carbon::yesterday();

        $seasons=Season::whereHas('league', function($query) use($league) {
                            $query->where('leagueName',$league);
                        })->get();
        $currentSeason=$seasons->last();

        if(!$request->month)
        {
            $matches=Match::where('season_id',$currentSeason->seasonID)
                            ->where('league_id',7)
                            ->whereBetween('startDate', [$yesterday,$forwardDate])
                            ->with(['team1' => function ($query) {
                                $query->select('teamID','name')->where('league_id', 7);
                            }])
                            ->with(['team2' => function ($query) {
                                $query->select('teamID','name')->where('league_id', 7);
                            }])
                            ->get();
        }
        else
        {
            if(Carbon::now()->month==Carbon::parse($request->month)->month)
            {
                $matches=Match::where('season_id',$currentSeason->seasonID)
                                ->where('league_id',7)
                                ->whereMonth('startDate','=',Carbon::parse($request->month)->month)
                                ->where('status','FINISHED')
                                ->with(['team1' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 7);
                                }])
                                ->with(['team2' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 7);
                                }])
                                ->get();

                $moreMatches=Match::where('season_id',$currentSeason->seasonID)
                                ->where('league_id',7)
                                ->where('startDate','<=',$forwardDate)
                                ->where('status','SCHEDULED')
                                ->with(['team1' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 7);
                                }])
                                ->with(['team2' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 7);
                                }])
                                ->get();

                $matches=$matches->merge($moreMatches);
            }
            else
            {
                $matches=Match::where('season_id',$currentSeason->seasonID)
                                ->where('league_id',7)
                                ->whereMonth('startDate','=',Carbon::parse($request->month)->month)
                                ->where('status','FINISHED')
                                ->with(['team1' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 7);
                                }])
                                ->with(['team2' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 7);
                                }])
                                ->get();
            }
        }

        $grouped = $matches->mapToGroups(function ($item, $key) {
            return [$item['startDate'] => $item];
        });

        $dt=Carbon::now();
        $points=0;
        foreach($grouped as $matches)
        {
            foreach($matches as $match)
            {
                $bet = Bet::where('game_id',$match['gameID'])
                            ->whereHas('user', function($query) use($request) {
                                $query->where('username',$request->username);
                            })->first();
                if($bet['status']=='UNCHECKED' && $match->winner!=null)
                {
                    if($match->winner=='HOME_TEAM' && $bet['team1score']>$bet['team2score'])
                    {
                        if($match->team1goals==$bet['team1score'] && $match->team2goals==$bet['team2score'])
                        {
                            $bet['status']='SUCCESS';
                            $points+=5;
                        }
                        else
                        {
                            $bet['status']='PARTIAL';
                            $points+=3;
                        }
                    }
                    else if($match->winner=='AWAY_TEAM' && $bet['team1score']<$bet['team2score'])
                    {
                        if($match->team1goals==$bet['team1score'] && $match->team2goals==$bet['team2score'])
                        {
                            $bet['status']='SUCCESS';
                            $points+=5;
                        }
                        else
                        {
                            $bet['status']='PARTIAL';
                            $points+=3;
                        }
                    }
                    else if($match->winner=='DRAW' && $bet['team1score']==$bet['team2score'])
                    {
                        if($match->team1goals==$bet['team1score'] && $match->team2goals==$bet['team2score'])
                        {
                            $bet['status']='SUCCESS';
                            $points+=5;
                        }
                        else
                        {
                            $bet['status']='PARTIAL';
                            $points+=3;
                        }
                    }
                    else
                        $bet['status']='FAILED';

                    $bet->save();
                }

                $match->userBet=$bet['status'];
                $match->userTeam1Bet=$bet['team1score'];
                $match->userTeam2Bet=$bet['team2score'];

                if($match->startDate > $dt)
                    $match->past=false;
                else
                    $match->past=true;
            }
        }

        $userScore=Score::where('league_id',7)
                        ->whereHas('user', function($query) use($request) {
                            $query->where('username',$request->username);
                        })->first();
        if($userScore)
        {
            $userScore->points+=$points;
            $userScore->save();
        }
        else
        {
            $userPoints=new Score();
            $userPoints->sport_id=$match->sport_id;
            $userPoints->league_id=$match->league_id;
            $userPoints->user_id=1;
            $userPoints->points=$points;
            $userPoints->save();
        }

        return json_encode($grouped);
    }

    public function showBasketBallMatches($request,$league)
    {
        $dt=Carbon::now();
        
        $today=Carbon::today();
        $forwardDate=$today->addDays(3);
        $yesterday=Carbon::yesterday();

        $seasons=Season::whereHas('league', function($query) use($league) {
                            $query->where('leagueName',$league);
                        })->get();
        $currentSeason=$seasons->last();

        if(!$request->month)
        {
            $matches=Match::where('season_id',$currentSeason->seasonID)
                            ->where('league_id',6)
                            ->whereBetween('startDate', [$yesterday,$forwardDate])
                            ->with(['team1' => function ($query) {
                                $query->select('teamID','name')->where('league_id', 6);
                            }])
                            ->with(['team2' => function ($query) {
                                $query->select('teamID','name')->where('league_id', 6);
                            }])
                            ->get();
        }
        else
        {
            if(Carbon::now()->month==Carbon::parse($request->month)->month)
            {
                $matches=Match::where('season_id',$currentSeason->seasonID)
                                ->where('league_id',6)
                                ->whereMonth('startDate','=',Carbon::parse($request->month)->month)
                                ->where('status','FINISHED')
                                ->with(['team1' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 6);
                                }])
                                ->with(['team2' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 6);
                                }])
                                ->get();

                $moreMatches=Match::where('season_id',$currentSeason->seasonID)
                                ->where('league_id',6)
                                ->where('startDate','<=',$forwardDate)
                                ->where('status','SCHEDULED')
                                ->with(['team1' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 6);
                                }])
                                ->with(['team2' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 6);
                                }])
                                ->get();

                $matches=$matches->merge($moreMatches);
            }
            else
            {
                $matches=Match::where('season_id',$currentSeason->seasonID)
                                ->where('league_id',6)
                                ->whereMonth('startDate','=',Carbon::parse($request->month)->month)
                                ->where('status','FINISHED')
                                ->with(['team1' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 6);
                                }])
                                ->with(['team2' => function ($query) {
                                    $query->select('teamID','name')->where('league_id', 6);
                                }])
                                ->get();
            }
        }

        $grouped = $matches->mapToGroups(function ($item, $key) {
            return [$item['startDate'] => $item];
        });

        $dt=Carbon::now();
        $points=0;
        foreach($grouped as $matches)
        {
            foreach($matches as $match)
            {
                $bet = Bet::where('game_id',$match['gameID'])
                            ->whereHas('user', function($query) use($request) {
                                $query->where('username',$request->username);
                            })->first();
                if($bet['status']=='UNCHECKED' && $match->winner!=null)
                {
                    if($match->winner=='HOME_TEAM' && $bet['team1score']>$bet['team2score'])
                    {
                        if($match->team1goals-$match->team2goals==$bet['otherScore'])
                        {
                            $bet['status']='SUCCESS';
                            $points+=5;
                        }
                        else
                        {
                            $bet['status']='PARTIAL';
                            $points+=3;
                        }
                    }
                    else if($match->winner=='AWAY_TEAM' && $bet['team1score']<$bet['team2score'])
                    {
                        if($match->team2goals-$match->team1goals==$bet['otherScore'])
                        {
                            $bet['status']='SUCCESS';
                            $points+=5;
                        }
                        else
                        {
                            $bet['status']='PARTIAL';
                            $points+=3;
                        }
                    }
                    else
                        $bet['status']='FAILED';

                    $bet->save();
                }

                $match->userBet=$bet['status'];
                $match->userTeam1Bet=$bet['team1score'];
                $match->userTeam2Bet=$bet['team2score'];
                $match->otherScore=$bet['otherScore'];

                if($match->startDate > $dt)
                    $match->past=false;
                else
                    $match->past=true;
            }
        }

        $userScore=Score::where('league_id',6)
                        ->whereHas('user', function($query) use($request) {
                            $query->where('username',$request->username);
                        })->first();
        if($userScore)
        {
            $userScore->points+=$points;
            $userScore->save();
        }
        else
        {
            $userPoints=new Score();
            $userPoints->sport_id=$match->sport_id;
            $userPoints->league_id=$match->league_id;
            $userPoints->user_id=1;
            $userPoints->points=$points;
            $userPoints->save();
        }

        return json_encode($grouped);
    }

    public function updateBasketballMatches()
    {
        $seasons=Season::where('league_id',6)->get();
        $currentSeason=$seasons->last();
        
        $date=new Carbon( $currentSeason->start ); 
        $year=$date->year;  

        $client = new Client();
        $result = $client->get('http://data.nba.net/data/10s/prod/v1/'.$year.'/schedule.json');
        $data=json_decode($result->getBody());

        foreach($data->league->standard as $match)
        {
            if($match->seasonStageId!=1 && $match->seasonStageId!=3)
            {
                $matchUpdate=Match::where('gameID',$match->gameId)->where('league_id',6)->first();
                if($matchUpdate)
                {
                    $matchUpdate->team1_id=$match->hTeam->teamId;
                    $matchUpdate->team2_id=$match->vTeam->teamId;
                    if($match->hTeam->score!="")
                    {
                        $matchUpdate->team1goals=$match->hTeam->score;
                        $matchUpdate->team2goals=$match->vTeam->score;
                        $matchUpdate->status="FINISHED";
                        if($match->hTeam->score>$match->vTeam->score)
                            $matchUpdate->winner="HOME_TEAM";
                        else
                            $matchUpdate->winner="AWAY_TEAM";
                    }
                    else
                        $matchUpdate->status="SCHEDULED";
                    
                    $matchUpdate->stage=$match->seasonStageId;
                    $matchUpdate->startDate=$match->startTimeUTC;

                    if(isset($match->playoffs))
                        $matchUpdate->groups=$match->playoffs->seriesId;
                    
                    $matchUpdate->save();
                }
                else
                {
                    $m=new Match();
                    $m->gameID=$match->gameId;
                    $m->season_id=$currentSeason->seasonID;
                    $m->sport_id=2;
                    $m->league_id=6;
                    $m->team1_id=$match->hTeam->teamId;
                    $m->team2_id=$match->vTeam->teamId;
                    if($match->hTeam->score!="")
                    {
                        $m->team1goals=$match->hTeam->score;
                        $m->team2goals=$match->vTeam->score;
                        $m->status="FINISHED";
                        if($match->hTeam->score>$match->vTeam->score)
                            $m->winner="HOME_TEAM";
                        else
                            $m->winner="AWAY_TEAM";
                    }
                    else
                        $m->status="SCHEDULED";
                    
                    $m->stage=$match->seasonStageId;
                    $m->startDate=$match->startTimeUTC;

                    if(isset($match->playoffs))
                        $m->groups=$match->playoffs->seriesId;

                    $m->save();
                }
            }
        }
    }

    public function getBasketballMatches()
    {
        $seasons=Season::where('league_id',6)->get();
        $currentSeason=$seasons->last();
        
        $date=new Carbon($currentSeason->start); 
        $year=$date->year;  

        $client = new Client();
        $result = $client->get('http://data.nba.net/data/10s/prod/v1/'.$year.'/schedule.json');
        $data=json_decode($result->getBody());

        foreach($data->league->standard as $match)
        {
            if($match->seasonStageId!=1 && $match->seasonStageId!=3)
            {
                $m=new Match();
                $m->gameID=$match->gameId;
                $m->season_id=$currentSeason->seasonID;
                $m->sport_id=2;
                $m->league_id=6;
                $m->team1_id=$match->hTeam->teamId;
                $m->team2_id=$match->vTeam->teamId;
                if($match->hTeam->score!="")
                {
                    $m->team1goals=$match->hTeam->score;
                    $m->team2goals=$match->vTeam->score;
                    $m->status="FINISHED";
                    if($match->hTeam->score>$match->vTeam->score)
                        $m->winner="HOME_TEAM";
                    else
                        $m->winner="AWAY_TEAM";
                }
                else
                    $m->status="SCHEDULED";
                
                $m->stage=$match->seasonStageId;
                $m->startDate=$match->startTimeUTC;

                if(isset($match->playoffs))
                    $m->groups=$match->playoffs->seriesId;

                $m->save();
            }
        }
    }

    public function getBasketballTeams()
    {
        $seasons=Season::where('league_id',6)->get();
        $currentSeason=$seasons->last();
        
        $date=new Carbon( $currentSeason->start ); 
        $year=$date->year;  

        $client = new Client();
        $result = $client->get('http://data.nba.net/data/10s/prod/v1/'.$year.'/teams.json');
        $data=json_decode($result->getBody());

        foreach($data->league->standard as $team)
        {
            if($team->isNBAFranchise)
            {
                $teamExists=Team::where('teamID',$team->teamId)->where('league_id',6)->get();
                if($teamExists->isEmpty())
                {
                    $t=new Team();
                    $t->sport_id=2;
                    $t->league_id=6;
                    $t->teamID=$team->teamId;
                    $t->name=$team->fullName;
                    $t->shortName=$team->nickname;
                    $t->save();
                }
            }
        }
    }

    public function getBasketballSeasons()
    {
        $client = new Client();
        $result = $client->get('http://data.nba.net/data/10s/prod/v1/calendar.json');
        $data=json_decode($result->getBody());
        
        $seasonExsists=Season::where('league_id',2)->where('seasonID',$data->startDate)->first();
        if(!$seasonExsists)
        {
            $season = new Season();
            $season->seasonID=$data->startDate;
            $season->sport_id=2;
            $season->league_id=6;
            $season->start=Carbon::create(substr($data->startDateCurrentSeason,0,4), 1, 1, 0)->toDateTimeString();
            $season->end=Carbon::create(substr($data->endDate,0,4), 1, 1, 0)->toDateTimeString();
            $season->save();
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$league)
    {
        if($request->sport=="basketball")
        {
            return $this->showBasketBallMatches($request,$league);
        }
        else if($request->sport=="football")
        {
            return $this->showFootballMatches($request,$league);
        }
        else if($request->sport=="icehockey")
        {
            return $this->showIcehockeyMatches($request,$league);
        }
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

    public function showFootballMatches($request,$league)
    {
        $date=Carbon::now();

        if(isset($request->wantedSeasonStart))
        {
            $currentMatchday=Season::whereHas('league', function($query) use($league) {
                                        $query->where('leagueName',$league);
                                    })->where('start','<=',$request->seasonStart)
                                    ->where('end','>=',$request->seasonEnd)
                                    ->first();
        }
        else
        {
            $currentMatchday=Season::whereHas('league', function($query) use($league) {
                                        $query->where('leagueName',$league);
                                    })->where('start','<=',$date)
                                    ->where('end','>=',$date)
                                    ->first();
        }
        if(isset($request->matchday))
        {
            $matches=Match::where('season_id',$currentMatchday->seasonID)
                            ->where('league_id',$currentMatchday->league_id)
                            ->where('matchday',$request->matchday)
                            ->where('startDate','<=',$date->addDays(16))
                            ->with(['team1:teamID,shortName' => function ($query) use($currentMatchday) {
                                $query->where('league_id', $currentMatchday->league_id);
                            }])
                            ->with(['team2:teamID,shortName' => function ($query) use($currentMatchday) {
                                $query->where('league_id', $currentMatchday->league_id);
                            }])
                            ->get();
        }
        else
        {
            $matches=Match::where('season_id',$currentMatchday->seasonID)
                            ->where('league_id',$currentMatchday->league_id)
                            ->where('matchday',$currentMatchday->currentMatchday)
                            ->where('startDate','<=',$date->addDays(16))
                            ->with(['team1' => function ($query) use($currentMatchday) {
                                $query->select('teamID','shortName')->where('league_id', $currentMatchday->league_id);
                            }])
                            ->with(['team2' => function ($query) use($currentMatchday) {
                                $query->select('teamID','shortName')->where('league_id', $currentMatchday->league_id);
                            }])
                            ->orderBy('startDate','ASC')
                            ->get();

            if($matches->count()<3)
            {
                $nextGame=Match::where('season_id',$currentMatchday->seasonID)
                                ->where('league_id',$currentMatchday->league_id)
                                ->where('status','SCHEDULED')
                                ->where('startDate','>',$matches->last()->startDate)->first();

                $moreMatches=Match::where('season_id',$currentMatchday->seasonID)
                                    ->where('league_id',$currentMatchday->league_id)
                                    ->where('matchday',$nextGame->matchday)
                                    ->with(['team1' => function ($query) use($currentMatchday) {
                                        $query->select('teamID','shortName')->where('league_id', $currentMatchday->league_id);
                                    }])
                                    ->with(['team2' => function ($query) use($currentMatchday) {
                                        $query->select('teamID','shortName')->where('league_id', $currentMatchday->league_id);
                                    }])
                                    ->orderBy('startDate','ASC')
                                    ->get();

                $matches=$matches->merge($moreMatches);
            }
        }

        $grouped = $matches->mapToGroups(function ($item, $key) {
            return [$item['startDate'] => $item];
        });

        $dt = Carbon::now();
        $points=0;
        foreach($grouped as $matches)
        {
            foreach($matches as $match)
            {
                $bet = Bet::where('game_id',$match['gameID'])
                            ->whereHas('user', function($query) use($request) {
                                $query->where('username',$request->username);
                            })->first();
                if($bet['status']=='UNCHECKED' && $match->winner!=null)
                {
                    if($match->winner=='HOME_TEAM' && $bet['team1score']>$bet['team2score'])
                    {
                        if($match->team1goals==$bet['team1score'] && $match->team2goals==$bet['team2score'])
                        {
                            $bet['status']='SUCCESS';
                            $points+=5;
                        }
                        else
                        {
                            $bet['status']='PARTIAL';
                            $points+=3;
                        }
                    }
                    else if($match->winner=='AWAY_TEAM' && $bet['team1score']<$bet['team2score'])
                    {
                        if($match->team1goals==$bet['team1score'] && $match->team2goals==$bet['team2score'])
                        {
                            $bet['status']='SUCCESS';
                            $points+=5;
                        }
                        else
                        {
                            $bet['status']='PARTIAL';
                            $points+=3;
                        }
                    }
                    else if($match->winner=='DRAW' && $bet['team1score']==$bet['team2score'])
                    {
                        if($match->team1goals==$bet['team1score'] && $match->team2goals==$bet['team2score'])
                        {
                            $bet['status']='SUCCESS';
                            $points+=5;
                        }
                        else
                        {
                            $bet['status']='PARTIAL';
                            $points+=3;
                        }
                    }
                    else
                        $bet['status']='FAILED';

                    $bet->save();
                    
                    $userScore=Score::where('league_id',$match->league_id)
                                    ->whereHas('user', function($query) use($request) {
                                        $query->where('username',$request->username);
                                    })->first();
                    if($userScore)
                    {
                        $userScore->points+=$points;
                        $userScore->save();
                    }
                    else
                    {
                        $userPoints=new Score();
                        $userPoints->sport_id=$match->sport_id;
                        $userPoints->league_id=$match->league_id;
                        $userPoints->user_id=1;
                        $userPoints->points=$points;
                        $userPoints->save();
                    }
                }

                $match->userBet=$bet['status'];
                $match->userTeam1Bet=$bet['team1score'];
                $match->userTeam2Bet=$bet['team2score'];

                if($match->startDate > $dt)
                    $match->past=false;
                else
                    $match->past=true;
            }
        }
        
        return json_encode($grouped->values());
    }

    public function getFootballMatches()
    {
        $leagueAPIid = collect([2021, 2014, 2019, 2015, 2002, 2001]);
        $leagueIDs = collect([1, 2, 3, 4, 8, 9]);

        $i=0;
        foreach($leagueAPIid as $league)
        {
            $matches=Football::getLeagueMatches($league);
            foreach($matches as $m)
            {
                $match = new Match();
                $match->gameID=$m->id;
                $match->season_id=$m->season->id;
                $match->sport_id=1;
                $match->league_id=$leagueIDs[$i];
                $match->team1_id=$m->homeTeam->id;
                $match->team2_id=$m->awayTeam->id;
                $match->team1goals=$m->score->fullTime->homeTeam;
                $match->team2goals=$m->score->fullTime->awayTeam;
                $match->status=$m->status;
                $match->winner=$m->score->winner;
                $match->matchday=$m->matchday;
                $match->startDate=new Carbon($m->utcDate);
                $match->stage=$m->stage;
                $match->groups=$m->group;
                $match->save();
            }
            $i+=1;
        }
    }

    public function getFootballSeasons()
    {
        $leagueAPIid = collect([2021, 2014, 2019, 2015, 2002, 2001]);
        $leagueIDs = collect([1, 2, 3, 4, 8, 9]);

        $i=0;
        foreach($leagueAPIid as $league)
        {
            $leagueSeason=Football::getLeague($league);

            $seasonExsists=Season::where('seasonID',$leagueSeason['currentSeason']->id)->where('league_id',$leagueIDs[$i])->first();
            if(!$seasonExsists)
            {
                $season = new Season();
                $season->seasonID=$leagueSeason['currentSeason']->id;
                $season->sport_id=1;
                $season->league_id=$leagueIDs[$i];
                $season->currentMatchday=$leagueSeason['currentSeason']->currentMatchday;
                $season->start=$leagueSeason['currentSeason']->startDate;
                $season->end=$leagueSeason['currentSeason']->endDate;
                $season->save();
            }
            
            $i+=1;
        }
    }

    public function footballMatchdayUpdate()
    {
        $leagueIDs = collect([1, 2, 3, 4, 8, 9]);

        foreach($leagueIDs as $leagueID)
        {
            $lastMatch=Match::where('league_id',$leagueID)->where('status','FINISHED')->orderBy('startDate','DESC')->first();

            $forward= new Carbon($lastMatch->startDate);
            $forward->addHours(24);

            if($lastMatch->startDate<Carbon::now()->subDays(1))
            {
                $leftMatches=Match::where('league_id',$leagueID)
                                    ->where('startDate','>',$lastMatch->startDate)
                                    ->where('startDate','<=',$forward)
                                    ->where('status','SCHEDULED')->get();
                                    
                if($leftMatches->count()>0)
                {
                    $currentMatchday=$leftMatches[0]->matchday;
                    $seasonID=$leftMatches[0]->season_id;
                }
                else
                {
                    $nextMatch=Match::Where('league_id',$leagueID)
                                    ->where('startDate','>',$forward)
                                    ->where('status','SCHEDULED')
                                    ->first();
                    $currentMatchday=$nextMatch->matchday;
                    $seasonID=$nextMatch->season_id;
                }
                $seasonMatchday=Season::where('seasonID',$seasonID)->first();
                $seasonMatchday->currentMatchday=$currentMatchday;
                $seasonMatchday->save();
            }
        }
    }

    public function updateFootballMatches()
    {
        $leagueAPIid = collect([2021, 2014, 2019, 2015, 2002, 2001]);
        $leagueIDs = collect([1, 2, 3, 4, 8, 9]);

        $datePast=Carbon::now()->subDays(7)->format('Y-m-d');
        $dateFuture=Carbon::now()->addDays(7)->format('Y-m-d');
        $filter = array(
            'dateFrom' => $datePast,
            'dateTo' => $dateFuture
          );

        $i=0;
        foreach($leagueAPIid as $index=>$league)
        {
            $matches = Football::getLeagueMatches($league, $filter);
            foreach($matches as $match)
            {
                $matchForUpdate=Match::where('gameID',$match->id)->where('league_id',$leagueIDs[$i])->first();
                if($matchForUpdate)
                {
                    $matchForUpdate->team1goals=$match->score->fullTime->homeTeam;
                    $matchForUpdate->team2goals=$match->score->fullTime->awayTeam;
                    $matchForUpdate->status=$match->status;
                    $matchForUpdate->winner=$match->score->winner;
                    $matchForUpdate->startDate=new Carbon($match->utcDate);;  
                    $matchForUpdate->save();
                }
                else
                {
                    $m = new Match();
                    $m->gameID=$match->id;
                    $m->season_id=$match->season->id;
                    $m->sport_id=1;
                    $m->league_id=$leagueIDs[$i];
                    $m->team1_id=$match->homeTeam->id;
                    $m->team2_id=$match->awayTeam->id;
                    $m->team1goals=$match->score->fullTime->homeTeam;
                    $m->team2goals=$match->score->fullTime->awayTeam;
                    $m->status=$match->status;
                    $m->winner=$match->score->winner;
                    $m->matchday=$match->matchday;
                    $m->startDate=new Carbon($match->utcDate);
                    $m->stage=$match->stage;
                    $m->groups=$match->group;
                    $m->save();
                }
            }
            $i+=1;
        }
    }

    public function getFootballTeams()
    {
        $leagueAPIid = collect([2021, 2014, 2019, 2015, 2002]);
        $leagueIDs = collect([1, 2, 3, 4, 8]);

        $i=0;
        foreach($leagueAPIid as $league)
        {
            $teams=Football::getLeagueTeams($league);
            foreach($teams as $t)
            {
                $teamExists=Team::where('teamID',$t->id)->where('league_id',$leagueIDs[$i])->get();
                if($teamExists->isEmpty())
                {
                    $team=new Team();
                    $team->sport_id=1;
                    $team->league_id=$leagueIDs[$i];
                    $team->teamID=$t->id;
                    $team->name=$t->name;
                    $team->shortName=$t->shortName;
                    $team->crest=$t->crestUrl;
                    $team->save();
                }
            }
            $i+=1;
        }
        
        $clteams=Football::getLeagueTeams(2001);
        foreach($clteams as $t)
        {
            $teamExists=Team::where('teamID',$t->id)->where('league_id',9)->first();
            if(!$teamExists)
            {
                $team=new Team();
                $team->sport_id=1;
                $team->league_id=9;
                $team->teamID=$t->id;
                $team->name=$t->name;
                $team->shortName=$t->shortName;
                $team->crest=$t->crestUrl;
                $team->save();
            }
        }
    }
}
