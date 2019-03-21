<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Season;
use App\FootballStanding;
use App\BasketballStanding;
use App\IcehockeyStanding;
use App\Match;
use Carbon\Carbon;
use Football;

class StandingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($league)
    {
        
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
    public function show($league)
    {
        if($league=="NBA")
        {
            return $this->showBasketballStandings($league);
        }
        else if($league=="NHL")
        {
            return $this->showIcehockeyStandings($league);
        }
        else
        {
            return $this->showFootballStandings($league);
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

    public function showIcehockeyStandings($league)
    {
        $dt=Carbon::now();
        $seasons=Season::whereHas('league', function($query) use($league) {
                            $query->where('leagueName',$league);
                        })->get();
        $currentSeason=$seasons->last();

        $standings=IcehockeyStanding::where('season_id',$currentSeason->seasonID)
                                    ->with('team:name,teamID,crest')
                                    ->orderBy('place','ASC')
                                    ->orderBy('group','ASC')
                                    ->get();

        $grouped = $standings->mapToGroups(function ($item, $key) {
            return [$item['group'] => $item];
        });
        
        $allStandings=array("groups"=>$grouped);

        return json_encode($allStandings);
    }
    

    public function showFootballStandings($league)
    {
        $dt=Carbon::now();
        $seasonID=Season::whereHas('league', function($query) use($league) {
                                    $query->where('leagueName',$league);
                                })->where('start','<=',$dt)->where('end','>=',$dt)->first();

        if($league!="ChampionsLeague")
        {
            $standings=FootballStanding::whereHas('league', function($query) use($league) {
                                    $query->where('leagueName',$league);
                                })->where('season_id',$seasonID['seasonID'])
                                ->with('team:shortName,teamID,crest')
                                ->orderBy('place','ASC')->get();

            return json_encode($standings);
        }
        else
        {
            $standings=FootballStanding::whereHas('league', function($query) use($league) {
                                    $query->where('leagueName',$league);
                                })->where('season_id',$seasonID['seasonID'])
                                ->with('team:shortName,teamID,crest')
                                ->orderBy('place','ASC')
                                ->orderBy('group','ASC')
                                ->get();

            $grouped = $standings->mapToGroups(function ($item, $key) {
                return [$item['group'] => $item];
            });

            $playoffs=Match::whereIn('stage',['ROUND_OF_16','ROUND_OF_8','ROUND_OF_4'])
                            ->where('season_id',$seasonID['seasonID'])
                            ->with('team1:teamID,shortName','team2:teamID,shortName')
                            ->get();
            if($playoffs->count()>0)
            {
                $playoffStages = $playoffs->mapToGroups(function ($item, $key) {
                    return [$item['stage'] => $item];
                });

                $stagesNames=$playoffStages->keys();
                foreach($playoffStages as $key=>$stage)
                {
                    $allPairs=collect([]);
                    foreach($stage as $index=>$match)
                    {
                        $match1=$stage->shift();
                        
                        $key = $stage->search(function($item) use($match1) {
                            return ($item->team1 == $match1->team2 && $item->team2 == $match1->team1);
                        });
                        
                        $match2=$stage->pull($key);

                        $stage=$stage->values();

                        $data = array(
                            "match1" => $match1,
                            "match2" => $match2
                          );
                        $pair = collect(["match1"=>$match1, "match2"=>$match2]);
                        $allPairs->push($pair);

                        if($stage->count()==0)
                            break;
                    }
                    $currentStage=$stagesNames[$key];
                    $playoffStages[$currentStage]=$allPairs;
                    
                    //return json_encode($t);
                }
            }

            $allStandings=array("groups"=>$grouped,"playoff"=>$playoffStages);

            return json_encode($allStandings);
        }
    }

    public function showBasketballStandings($league)
    {
        $dt=Carbon::now();
        $seasons=Season::whereHas('league', function($query) use($league) {
                            $query->where('leagueName',$league);
                        })->get();
        $currentSeason=$seasons->last();

        $standings=BasketballStanding::where('season_id',$currentSeason->seasonID)
                                    ->with('team:name,teamID,crest')
                                    ->orderBy('place','ASC')
                                    ->orderBy('group','ASC')
                                    ->get();

        $grouped = $standings->mapToGroups(function ($item, $key) {
            return [$item['group'] => $item];
        });             


        $playoffs=Match::where('stage',4)
                        ->where('season_id',$currentSeason->seasonID)
                        ->with('team1:teamID,name','team2:teamID,name')
                        ->get();

        if($playoffs->count()>0)
        {
            $playoffStages = $playoffs->mapToGroups(function ($item, $key) {
                return [$item['groups'] => $item];
            });

            foreach($playoffStages as $pair)
            {
                $team1wins=0;
                $team2wins=0;
                $team1id=$pair[0]->team1->teamID;
                foreach($pair as $pairMatches)
                {
                    if($pairMatches->team1goals>$pairMatches->team2goals)
                    {
                        if($team1id==$pairMatches->team1->teamID)
                            $team1wins+=1;
                        else
                            $team2wins+=1;
                    }
                    else
                    {
                        if($team1id==$pairMatches->team2->teamID)
                            $team1wins+=1;
                        else
                            $team2wins+=1;
                    }
                }
                $pair->put('team1wins', $team1wins);
                $pair->put('team2wins', $team2wins);
            }

            $playoffs=array("ROUND_OF_8"=>array($playoffStages->shift(),$playoffStages->shift(),
                                        $playoffStages->shift(),$playoffStages->shift(),
                                        $playoffStages->shift(),$playoffStages->shift(),
                                        $playoffStages->shift(),$playoffStages->shift()),
                            "ROUND_OF_4"=>array($playoffStages->shift(),$playoffStages->shift(),
                                                $playoffStages->shift(),$playoffStages->shift()),
                            "ROUND_OF_2"=>array($playoffStages->shift(),$playoffStages->shift()),
                            "FINAL"=>array($playoffStages->shift())
                    );

            $allStandings=array("groups"=>$grouped,"playoffs"=>$playoffs);
        }
        else
            $allStandings=array("groups"=>$grouped);

        return json_encode($allStandings);
    }

    public function getFootballStandings()
    {
        $leagueAPIid = collect([2021, 2014, 2019, 2015, 2002, 2001]);
        $leagueIDs = collect([1, 2, 3, 4, 8, 9]);
        $dt=Carbon::now();

        foreach($leagueAPIid as $index=>$leagueAPI)
        {
            $seasonID=Season::where('league_id',$leagueIDs[$index])->where('start','<=',$dt)->where('end','>=',$dt)->first();
            
            $standingExists=FootballStanding::where('season_id',$seasonID['seasonID'])->first();
            if(!$standingExists)
            {
                $standings=Football::getLeagueStandings($leagueAPI);
                foreach($standings as $standingType)
                {
                    if($standingType->type=='TOTAL')
                    {
                        foreach($standingType->table as $teamStanding)
                        {
                            $standing=new FootballStanding();
                            $standing->sport_id=1;
                            $standing->league_id=$leagueIDs[$index];
                            $standing->season_id=$seasonID['seasonID'];
                            $standing->team_id=$teamStanding->team->id;
                            $standing->place=$teamStanding->position;
                            $standing->gamesPlayed=$teamStanding->playedGames;
                            $standing->scoredGoals=$teamStanding->goalsFor;
                            $standing->concedeGoals=$teamStanding->goalsAgainst;
                            $standing->won=$teamStanding->won;
                            $standing->draw=$teamStanding->draw;
                            $standing->lost=$teamStanding->lost;
                            $standing->points=$teamStanding->points;
                            $standing->group=$standingType->group;
                            $standing->save();
                        }
                    }
                }
            }
        }
    }

    public function getBasketballStandings()
    {
        $seasons=Season::where('league_id',6)->get();
        $season=$seasons->last();

        $basketballStandingsExsists=BasketballStanding::where('season_id',$season['seasonID'])->first();

        if(!$basketballStandingsExsists)
        {
            $client = new Client();
            $result = $client->get('http://data.nba.net/data/10s/prod/v1/current/standings_conference.json');
            $data=json_decode($result->getBody());

            foreach($data->league->standard->conference->east as $eastConf)
            {
                $standing=new BasketballStanding();
                $standing->sport_id=2;
                $standing->league_id=6;
                $standing->season_id=$season['seasonID'];
                $standing->team_id=$eastConf->teamId;
                $standing->place=$eastConf->confRank;
                $standing->gamesPlayed=$eastConf->win+$eastConf->loss;
                $standing->winPrct=$eastConf->winPct;
                $standing->gamesBehind=$eastConf->gamesBehind;

                if($eastConf->isWinStreak)
                    $standing->streak=$eastConf->streak;
                else
                    $standing->streak=$eastConf->streak*(-1);
                
                $standing->won=$eastConf->win;
                $standing->lost=$eastConf->loss;
                $standing->group="EAST";
                $standing->save();
            }

            foreach($data->league->standard->conference->west as $westConf)
            {
                $standing=new BasketballStanding();
                $standing->sport_id=2;
                $standing->league_id=6;
                $standing->season_id=$season['seasonID'];
                $standing->team_id=$westConf->teamId;
                $standing->place=$westConf->confRank;
                $standing->gamesPlayed=$westConf->win+$westConf->loss;
                $standing->winPrct=$westConf->winPct;
                $standing->gamesBehind=$westConf->gamesBehind;

                if($westConf->isWinStreak)
                    $standing->streak=$westConf->streak;
                else
                    $standing->streak=$westConf->streak*(-1);
                
                $standing->won=$westConf->win;
                $standing->lost=$westConf->loss;
                $standing->group="WEST";
                $standing->save();
            }
        }
    }

    public function updateFootballStandings()
    {
        $leagueAPIid = collect([2021, 2014, 2019, 2015, 2002, 2001]);
        $leagueIDs = collect([1, 2, 3, 4, 8, 9]);
        $dt=Carbon::now();
        
        foreach($leagueAPIid as $index=>$leagueAPI)
        {
            $seasonID=Season::where('league_id',$leagueIDs[$index])->where('start','<=',$dt)->where('end','>=',$dt)->first();
            $standingExists=FootballStanding::where('season_id',$seasonID['seasonID'])->first();
            if($standingExists)
            {
                $standings=Football::getLeagueStandings($leagueAPI);
                foreach($standings as $standingType)
                {
                    if($standingType->type=='TOTAL')
                    {
                        foreach($standingType->table as $teamStanding)
                        {
                            $standing=FootballStanding::where('season_id',$seasonID['seasonID'])->where('team_id',$teamStanding->team->id)->first();
                            $standing->won=$teamStanding->won;
                            $standing->draw=$teamStanding->draw;
                            $standing->lost=$teamStanding->lost;
                            $standing->points=$teamStanding->points;
                            $standing->place=$teamStanding->position;
                            $standing->gamesPlayed=$teamStanding->playedGames;
                            $standing->scoredGoals=$teamStanding->goalsFor;
                            $standing->concedeGoals=$teamStanding->goalsAgainst;
                            $standing->save();
                        }
                    }
                }
            }
        }
    }

    public function updateBasketballStandings()
    {
        $seasons=Season::where('league_id',6)->get();
        $season=$seasons->last();

        $basketballStandingsExsists=BasketballStanding::where('season_id',$season['seasonID'])->first();

        if($basketballStandingsExsists)
        {
            $client = new Client();
            $result = $client->get('http://data.nba.net/data/10s/prod/v1/current/standings_conference.json');
            $data=json_decode($result->getBody());

            foreach($data->league->standard->conference->east as $eastConf)
            {
                $standing=BasketballStanding::where('season_id',$season['seasonID'])->where('team_id',$eastConf->teamId)->first();
                $standing->place=$eastConf->confRank;
                $standing->gamesPlayed=$eastConf->win+$eastConf->loss;
                $standing->winPrct=$eastConf->winPct;
                $standing->gamesBehind=$eastConf->gamesBehind;

                if($eastConf->isWinStreak)
                    $standing->streak=$eastConf->streak;
                else
                    $standing->streak=$eastConf->streak*(-1);
                
                $standing->won=$eastConf->win;
                $standing->lost=$eastConf->loss;
                $standing->save();
            }

            foreach($data->league->standard->conference->west as $westConf)
            {
                $standing=BasketballStanding::where('season_id',$season['seasonID'])->where('team_id',$westConf->teamId)->first();
                $standing->place=$westConf->confRank;
                $standing->gamesPlayed=$westConf->win+$westConf->loss;
                $standing->winPrct=$westConf->winPct;
                $standing->gamesBehind=$westConf->gamesBehind;

                if($westConf->isWinStreak)
                    $standing->streak=$westConf->streak;
                else
                    $standing->streak=$westConf->streak*(-1);
                
                $standing->won=$westConf->win;
                $standing->lost=$westConf->loss;
                $standing->save();
            }
        }
    }

    public function updateIceHockeyStandings()
    {
        $seasons=Season::where('league_id',7)->get();
        $currentSeason=$seasons->last();

        $seasonsStandingsExsists=IcehockeyStanding::where('season_id',$currentSeason->seasonID)->first();

        if($seasonsStandingsExsists)
        {
            $client = new Client();
            $result = $client->get('https://statsapi.web.nhl.com/api/v1/standings');
            $data=json_decode($result->getBody());

            foreach($data->records as $standings)
            {
                if($standings->conference->name=="Eastern")
                    $group="EAST";
                else
                    $group="WEST";
                foreach($standings->teamRecords as $standing)
                {
                    $standingExists=IcehockeyStanding::where('season_id',$currentSeason->seasonID)->where('team_id',$standing->team->id)->first();

                    $standingExists->place=$standing->conferenceRank;
                    $standingExists->points=$standing->points;
                    $standingExists->won=$standing->leagueRecord->wins;
                    $standingExists->lost=$standing->leagueRecord->losses;
                    $standingExists->overtime=$standing->leagueRecord->ot;
                    $standingExists->scoredGoals=$standing->goalsScored;
                    $standingExists->concedeGoals=$standing->goalsAgainst;
                    $standingExists->save();
                }
            }
        }
    }

    public function getIceHockeyStandings()
    {
        $seasons=Season::where('league_id',7)->get();
        $currentSeason=$seasons->last();

        $seasonsStandingsExsists=IcehockeyStanding::where('season_id',$currentSeason->seasonID)->first();

        if(!$seasonsStandingsExsists)
        {
            $client = new Client();
            $result = $client->get('https://statsapi.web.nhl.com/api/v1/standings');
            $data=json_decode($result->getBody());

            foreach($data->records as $standings)
            {
                if($standings->conference->name=="Eastern")
                    $group="EAST";
                else
                    $group="WEST";
                foreach($standings->teamRecords as $standing)
                {
                    $icehockeyStandings=new IcehockeyStanding();
                    $icehockeyStandings->sport_id=3;
                    $icehockeyStandings->league_id=7;
                    $icehockeyStandings->team_id=$standing->team->id;
                    $icehockeyStandings->season_id=$currentSeason->seasonID;
                    $icehockeyStandings->place=$standing->conferenceRank;
                    $icehockeyStandings->points=$standing->points;
                    $icehockeyStandings->gamesPlayed=$standing->gamesPlayed;
                    $icehockeyStandings->won=$standing->leagueRecord->wins;
                    $icehockeyStandings->lost=$standing->leagueRecord->losses;
                    $icehockeyStandings->overtime=$standing->leagueRecord->ot;
                    $icehockeyStandings->scoredGoals=$standing->goalsScored;
                    $icehockeyStandings->concedeGoals=$standing->goalsAgainst;
                    $icehockeyStandings->group=$group;
                    $icehockeyStandings->save();
                }
            }
        }
    }
}
