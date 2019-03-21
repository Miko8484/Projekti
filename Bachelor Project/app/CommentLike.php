<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $table = 'comment_likes';
    public $timestamps = false;

    public function users(){
        return $this->hasMany(User::class);
    }

    public function forumComments(){
        return $this->hasMany(ForumComment::class);
    }
}
