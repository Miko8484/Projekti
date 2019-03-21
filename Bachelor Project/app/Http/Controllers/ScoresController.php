<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Score;
use App\Http\Resources\Score as ScoreResource;

class ScoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = json_decode($request->filter, true);
        if($request->sort=="username")
            $sortColumn="users.username";
        else
            $sortColumn="points";

        if($data)
        {
            if($request->leagues=='All')
            {
                $filteredScores = Score::whereHas('user', function($query) use($data) {
                                            $query->where('username','LIKE',"%{$data['username']}%");
                                        })
                                        ->select('user_id',DB::raw('SUM(points) as points'))
                                        ->join('users', 'users.id', '=', 'scores.user_id')
                                        ->groupBy('user_id')
                                        ->orderBy($sortColumn, $request->direction)->paginate(3);
            }
            else
            {
                $filteredScores = Score::whereHas('league', function($query) use($request) {
                                            $query->whereIn('LeagueName',str_replace(' ', '', explode(',',$request->leagues)));
                                        })
                                        ->whereHas('user', function($query) use($data) {
                                            $query->where('username','LIKE',"%{$data['username']}%");
                                        })
                                        ->select('user_id',DB::raw('SUM(points) as points'))
                                        ->join('users', 'users.id', '=', 'scores.user_id')
                                        ->groupBy('user_id')
                                        ->orderBy($sortColumn, $request->direction)->paginate(3);
            }
        }
        else
        {
            if($request->leagues=='All')
            {
                $filteredScores = Score::select('user_id',DB::raw('SUM(points) as points'))
                                 ->join('users', 'users.id', '=', 'scores.user_id')
                                 ->groupBy('user_id')
                                 ->orderBy($sortColumn,$request->direction)->paginate(3);
            }
            else
            {
                $filteredScores = Score::whereHas('league', function($query) use($request) {
                                      $query->whereIn('LeagueName',str_replace(' ', '', explode(',',$request->leagues)));
                                  })->select('user_id',DB::raw('SUM(points) as points'))
                                  ->join('users', 'users.id', '=', 'scores.user_id')
                                  ->groupBy('user_id')
                                  ->orderBy($sortColumn, $request->direction)->paginate(3);
            }
        }

        $all = Score::select('user_id',DB::raw('SUM(points) as points'))
                                ->join('users', 'users.id', '=', 'scores.user_id')
                                ->groupBy('user_id')
                                ->orderBy("points",$request->direction)->get();
        
        if($request->direction=='desc')
        {
            $number = 1; 
            foreach($all as $a) 
            { $a->place = $number++; }
        }
        else
        {
            $number = $all->count();
            foreach($all as $a) 
            { $a->place = $number--; }
        }

        foreach($filteredScores as $score){ 
            $score->username = $score->user->username;
            foreach($all as $a){
                $a->username=$a->user->username;
                if($score->username==$a->username){
                    $score->place = $a->place;
                    break;
                }
            }
        }

        return json_encode($filteredScores);
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
    public function show($id, $leagues)
    {
        if($leagues=='All')
        {
            $all=DB::table('scores')->select('users.username', DB::raw('SUM(points) as points'))
                                        ->join('users', 'users.id', '=', 'scores.user_id') 
                                        ->join('sports', 'sports.id', '=', 'scores.sport_id')
                                        ->join('leagues', 'leagues.id', '=', 'scores.league_id')
                                        ->groupBy('users.username')
                                        ->orderBy('points','desc')->get();

            $user=DB::table('scores')->select('users.username', DB::raw('SUM(points) as points'))
                                        ->join('users', 'users.id', '=', 'scores.user_id') 
                                        ->join('sports', 'sports.id', '=', 'scores.sport_id')
                                        ->join('leagues', 'leagues.id', '=', 'scores.league_id')
                                        ->groupBy('users.username')
                                        ->where('users.username',$id)->get();
        }
        else
        {
            $all=DB::table('scores')->select('users.username', DB::raw('SUM(points) as points'))
                                    ->join('users', 'users.id', '=', 'scores.user_id') 
                                    ->join('sports', 'sports.id', '=', 'scores.sport_id')
                                    ->join('leagues', 'leagues.id', '=', 'scores.league_id')
                                    ->whereIn('leagues.leagueName', explode(',',$leagues))
                                    ->groupBy('users.username')
                                    ->orderBy("points", 'desc')->get();
            
            $user=DB::table('scores')->select('users.username', DB::raw('SUM(points) as points'))
                                        ->join('users', 'users.id', '=', 'scores.user_id') 
                                        ->join('sports', 'sports.id', '=', 'scores.sport_id')
                                        ->join('leagues', 'leagues.id', '=', 'scores.league_id')
                                        ->whereIn('leagues.leagueName', explode(',',$leagues))
                                        ->groupBy('users.username')
                                        ->where('users.username',$id)->get();; 
        }

        if($user->count()==0)
            return "Null";

        $number=1;
        foreach($all as $a)
        {
            if($a->username == $user[0]->username)
            {
                $user[0]->place = $number++;
                break;
            }
            else
                $number++;
        }
        
        return json_encode($user); 
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
