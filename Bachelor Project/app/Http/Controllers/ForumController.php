<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ForumPost;
use App\ForumTheme;
use App\PostLike;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class ForumController extends Controller
{
    public function checkLikes(Request $request)
    {
        $post = ForumPost::where('postHash',$request->postHash)->where('shortTitle',$request->shortTitle)->first();
        $user = User::where('username',$request->username)->first();

        $like = PostLike::where('user_id',$user->id)->where('post_id',$post->id)->where('action','like')->first();
        if(!$like)
            $likeReturn=false;
        else
            $likeReturn=true;
        
        $dislike = PostLike::where('user_id',$user->id)->where('post_id',$post->id)->where('action','dislike')->first();
        if(!$dislike)
            $dislikeReturn=false;
        else
            $dislikeReturn=true;

        $data=array("like"=>$likeReturn,"dislike"=>$dislikeReturn);
        return json_encode($data);
    }

    public function likeAction(Request $request)
    {
        $post = ForumPost::where('postHash',$request->postHash)->where('shortTitle',$request->shortTitle)->first();
        $user = User::where('username',$request->username)->first();

        if($request->action=='like')
        {
            $findDislike = PostLike::where('user_id',$user->id)->where('post_id',$post->id)->where('action','dislike')->first();
            if($findDislike)
            {
                $findDislike->delete();
                $post->dislikes -= 1;
            }

            $findLike = PostLike::where('user_id',$user->id)->where('post_id',$post->id)->where('action','like')->first();
            if($findLike)
            {
                $findLike->delete();
                $post->likes-=1;
                $post->save();
                $likeButton=false;
                $dislikeButton=false;
            }
            else
            {
                $like = new PostLike();

                $post->likes += 1;
                $post->save();

                $like->user_id=$user->id;
                $like->post_id=$post->id;
                $like->action='like';
                $like->save();

                $likeButton=true;
                $dislikeButton=false;
            }
                
        }
        else if($request->action=='dislike')
        {
            $findLike = PostLike::where('user_id',$user->id)->where('post_id',$post->id)->where('action','like')->first();
            if($findLike)
            {
                $findLike->delete();
                $post->likes -= 1;
            }

            $findDislike = PostLike::where('user_id',$user->id)->where('post_id',$post->id)->where('action','dislike')->first();
            if($findDislike)
            {
                $findDislike->delete();
                $post->dislikes -= 1;
                $post->save();
                $likeButton=false;
                $dislikeButton=false;
            }
            else
            {
                $like = new PostLike();

                $post->dislikes += 1;
                $post->save();

                $like->user_id=$user->id;
                $like->post_id=$post->id;
                $like->action='dislike';
                $like->save();

                $likeButton=false;
                $dislikeButton=true;
            }
        }
        else
            return "There was an error, try again later";
        $data=array($likeButton,$post->likes,$dislikeButton,$post->dislikes);
        return json_encode($data);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->findPost!='')
        {
            if($request->theme='all')
                $posts = ForumPost::where('title','LIKE',"%{$request->findPost}%")
                                    ->orderBy($request->filterBy,$request->filterDirection)->get();
        }
        else
        {
            if($request->theme=='all')
                $posts = ForumPost::orderBy($request->filterBy,$request->filterDirection)->get();
            else
            {
                
                $posts = ForumPost::whereHas('forumTheme', function($query) use($request) {
                                        $query->where('theme',$request->theme);
                                    })
                                    ->orderBy($request->filterBy,$request->filterDirection)->get();
            }
        }

        foreach($posts as $post)
        {
            $post->user=$post->user;
            $post->forumTheme;
        }
        
        return json_encode($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string',
            'theme' => 'required'
        ]);

        $theme = ForumTheme::where('theme',$request->theme)->first();
        $secret = str_random(8);

        $post = new ForumPost();

        $shortTitle = str_replace(' ', '_', strip_tags(htmlspecialchars_decode($request->title)));
        $shortTitle = preg_replace('/[^A-Za-z0-9\_]/', '', $shortTitle);
        $shortTitle = preg_replace('/_+/', '_', $shortTitle);
        
        $post->title=strip_tags(htmlspecialchars_decode($request->title));
        $post->content=$request->content;
        $post->user_id=Auth::user()->id;
        $post->theme_id=$theme->id;
        $post->postHash=$secret;
        $post->shortTitle=$shortTitle;

        $post->save();

        return json_encode(array('secret' => $secret, 'shortTitle' => $shortTitle));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$postHash,$shortTitle)
    {
        //$title = str_replace('_', ' ', $title); 
        $post = ForumPost::where('postHash',$postHash)->where('shortTitle',$shortTitle)->first();
        $post->views += 1;
        $post->update();

        $post->user=$post->user;
        $post->forumTheme;

        return json_encode($post);
    }

    public function showPost(Request $request,$id)
    {
        /*$post = ForumPost::where('id',$id)

        return json_encode($post);*/
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
    public function update(Request $request, $postHash,$shortTitle)
    {
        $post = ForumPost::where('postHash',$postHash)->where('shortTitle',$shortTitle)->first();
        $theme = ForumTheme::where('theme',$request->theme)->first();

        $post->theme_id = $theme->id;
        $post->content=$request->content;

        $post->update();
        return "Post updated successfully.";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($postHash,$shortTitle)
    {
        $post = ForumPost::where('postHash',$postHash)->where('shortTitle',$shortTitle)->first();
        $post->delete();

        return "Post deleted successfully.";
    }
}
