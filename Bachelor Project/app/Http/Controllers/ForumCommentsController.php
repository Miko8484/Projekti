<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ForumPost;
use App\ForumComment;
use App\CommentLike;
use Response;

class ForumCommentsController extends Controller
{
    public function likeAction(Request $request)
    {
        $user=User::where('username',$request->username)->first();
        $comment=ForumComment::where('id',$request->commentId)->first();
        $findLike = CommentLike::where('user_id',$user->id)->where('comment_id',$comment->id)->first();

        if($findLike)
        {
            $findLike->delete();
            $comment->likes -= 1;
            $comment->save();
            $likeButton=false;
        }
        else
        {
            $like = new CommentLike();

            $comment->likes += 1;
            $comment->save();

            $like->user_id=$user->id;
            $like->comment_id=$comment->id;
            $like->save();

            $likeButton=true;
        }

        $data=array($likeButton,$comment->likes);
        return json_encode($data);
    }

    public function addReply(Request $request)
    {
        $comment = ForumComment::where('id',$request->commentId)->first();
        $user = User::where('username',$request->username)->first();

        $reply = new ForumComment();
        $reply->post_id=$comment->post_id;
        $reply->parent_id=$comment->id;
        $reply->user_id=$user->id;
        $reply->comment=$request->content;

        $reply->save();
    }

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
        $http = new \GuzzleHttp\Client;

        $response = $http->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=>
                [
                    'secret'=>'6Ld7M5AUAAAAAFwVShOI7cRvRnhUdvgvrItEZTpg',
                    'response'=>$request->token
                 ]
            ]
        );
    
        $body = json_decode((string)$response->getBody());

        if($body->score>'0.5')
        {
            $user = User::where('username',$request->username)->first();
            $post = ForumPost::where('postHash',$request->postHash)->where('shortTitle',$request->shortTitle)->first();

            $comment = new ForumComment();
            $comment->user_id = $user->id;
            $comment->post_id = $post->id;
            $comment->comment = $request->comment;

            $comment->save();

            $post->commentsNumber+=1;
            $post->save();
        }
        else
            return Response::json("Looks like you are a robot.",400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$postHash,$shortTitle)
    {
        $user = User::where('username',$request->user)->first();

        $comments = ForumComment::whereHas('forumPost', function($query) use($postHash,$shortTitle) {
                                    $query->where('postHash',$postHash)->where('shortTitle',$shortTitle);
                                })->get();

        foreach($comments as $comment)
        {
            $comment->user = $comment->user;

            if($comment->parent_id)
            {
                $parent=$comments->where('id',$comment->parent_id)->first(); 
                $userReplyingTo=$parent->user->username;

                $strippedComment=str_replace(['<p>', '</p>'], '', $comment->comment);
                $comment->comment = "<p><a href='/user/".$userReplyingTo."' stlye='color:red'>@$userReplyingTo </a>".$strippedComment."</p>";
            }

            if($comment->user->username==$user->username)
                $comment->liked = true;
            else
            {
                $findLike = CommentLike::where('user_id',$user->id)->where('comment_id',$comment->id)->first();
                if($findLike)
                    $comment->liked = true;
                else
                    $comment->liked = false;
            }
        }

        return json_encode($comments);
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
        $comment=ForumComment::where('id',$id)->first();
        $comment->comment="[comment deleted]";
        $comment->save();

        $commentLikes=CommentLike::where('comment_id',$id)->delete();
    }
}
