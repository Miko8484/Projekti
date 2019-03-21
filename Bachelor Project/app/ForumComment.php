<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\ForumPost;
use App\CommentLike;

class ForumComment extends Model
{
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function forumPost(){
        return $this->belongsTo(ForumPost::class,'post_id');
    }

    public function commentLikes(){
        return $this->hasMany(CommentLike::class);
    }
}
