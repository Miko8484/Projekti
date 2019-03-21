<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ForumPost;
use App\Score;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Response;

class UsersController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAccount($id)
    {
        $id=Auth::user()->id;
        $user=User::select('username','email','avatar')->where('id', $id)->first();
        $forumPosts=ForumPost::where('user_id',$id)->get();

        foreach($forumPosts as $post)
        {
            $post->user=$post->user;
            $post->forumTheme;
        }

        
        $filteredScoresSport = Score::select('sport_id',DB::raw('SUM(points) as points'))
                                    ->where('user_id',$id)
                                    ->groupBy('sport_id')->with('sport')->get();
        
        foreach($filteredScoresSport as $score)
        {
            $leagues = Score::select('league_id',DB::raw('SUM(points) as points'))
                                    ->where('user_id',$id)
                                    ->where('sport_id',$score->sport_id)
                                    ->groupBy('league_id')->with('league')->get();
            $leaguesList = [];
            foreach($leagues as $l)
            {
                $leaguesList[] = ['league' => $l->league->leagueName,'points'=>$l->points];
            }
            $score->leagues = $leaguesList;
        }

        $all = Score::select('user_id',DB::raw('SUM(points) as points'))
                                ->join('users', 'users.id', '=', 'scores.user_id')
                                ->groupBy('user_id')
                                ->orderBy("points",'DESC')->get();

        $number = 1; 
        foreach($all as $a) 
        { $a->place = $number++; }

        foreach($filteredScoresSport as $score){ 
            foreach($all as $a){
                $a->username=$a->user->username;
                if($user->username==$a->username){
                    $score->place = $a->place;
                    break;
                }
            }
        }

        $data=array('user'=>$user,'scores'=>$filteredScoresSport,'forumPosts'=>$forumPosts);
        return json_encode($data); 
    }

    public function showProfile($username)
    {
        $user=User::select('id','username','email','avatar')->where('username', $username)->first();

        if($user)
        {
            $forumPosts=ForumPost::where('user_id',$user->id)->get();

            foreach($forumPosts as $post)
            {
                $post->user=$post->user;
                $post->forumTheme;
            }

            
            $filteredScoresSport = Score::select('sport_id',DB::raw('SUM(points) as points'))
                                        ->where('user_id',$user->id)
                                        ->groupBy('sport_id')->with('sport')->get();
            
            foreach($filteredScoresSport as $score)
            {
                $leagues = Score::select('league_id',DB::raw('SUM(points) as points'))
                                        ->where('user_id',$user->id)
                                        ->where('sport_id',$score->sport_id)
                                        ->groupBy('league_id')->with('league')->get();
                $leaguesList = [];
                foreach($leagues as $l)
                {
                    $leaguesList[] = ['league' => $l->league->leagueName,'points'=>$l->points];
                }
                $score->leagues = $leaguesList;
            }

            $all = Score::select('user_id',DB::raw('SUM(points) as points'))
                                    ->join('users', 'users.id', '=', 'scores.user_id')
                                    ->groupBy('user_id')
                                    ->orderBy("points",'DESC')->get();

            $number = 1; 
            foreach($all as $a) 
            { $a->place = $number++; }

            foreach($filteredScoresSport as $score){ 
                foreach($all as $a){
                    $a->username=$a->user->username;
                    if($user->username==$a->username){
                        $score->place = $a->place;
                        break;
                    }
                }
            }

            $data=array('user'=>$user,'scores'=>$filteredScoresSport,'forumPosts'=>$forumPosts);
            return json_encode($data); 
        }
        else
        {
            return Response::json("User with username ".$username." doesn't exsists.", 404);
            //return json_encode("User with username ".$username." doesn't exsists",404);
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
        $user=User::select('username','email','avatar')->where('username', $id)->get();

        $fileNameToStore = $user[0]->avatar;

        if($request->avatar){
            $filenameWithExt = $request->avatar->getClientOriginalName();;
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->avatar->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $path = $request->avatar->storeAs('/public/profile_images', $fileNameToStore);
        }

        if($request->oldUsername == $request->username)
        {
            if($user[0]->email == $request->email)
            {
                $request->validate([
                    'password' => 'string|min:6|nullable',
                    'avatar' => 'image|mimes:jpg,jpeg,png|nullable|max:1999'
                ]);
                if($request->password)
                    User::where('username', '=', $id)->update(array('password' => Hash::make($request->password),
                                                                    'avatar' => $fileNameToStore));
                else
                    User::where('username', '=', $id)->update(array('avatar' => $fileNameToStore));
            }
            else
            {
                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'string|min:6|nullable',
                    'avatar' => 'image|mimes:jpg,jpeg,png|nullable|max:1999'
                ]);
                if($request->password)
                    User::where('username', '=', $id)->update(array('email' => $request->email, 
                                                                    'password' => Hash::make($request->password),
                                                                    'avatar' => $fileNameToStore));
                else
                    User::where('username', '=', $id)->update(array('email' => $request->email, 
                                                                    'avatar' => $fileNameToStore));
            }
        }
        else
        {
            if($user[0]->email == $request->email)
            {
                $request->validate([
                    'username' => 'required|string|max:255|unique:users',
                    'password' => 'string|min:6|nullable',
                    'avatar' => 'image|mimes:jpg,jpeg,png|nullable|max:1999'
                ]);

                if($request->password)
                    User::where('username', '=', $id)->update(array('username' => $request->username,
                                                                'password' => Hash::make($request->password),
                                                                'avatar' => $fileNameToStore));
                else
                    User::where('username', '=', $id)->update(array('username' => $request->username,
                                                                    'avatar' => $fileNameToStore));
            }
            else
            {
                $request->validate([
                    'username' => 'required|string|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'string|min:6|nullable',
                    'avatar' => 'image|mimes:jpg,jpeg,png|nullable|max:1999'
                ]);

                if($request->password)
                    User::where('username', '=', $id)->update(array('email' => $request->email,
                                                                    'username' => $request->username,
                                                                    'password' => Hash::make($request->password),
                                                                    'avatar' => $fileNameToStore));
                else
                    User::where('username', '=', $id)->update(array('email' => $request->email,
                                                                    'username' => $request->username,
                                                                    'avatar' => $fileNameToStore));
            }
            
        }

        return json_encode($fileNameToStore);
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
