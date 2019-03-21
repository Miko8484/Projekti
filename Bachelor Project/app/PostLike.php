<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\ForumPost;

class PostLike extends Model
{
    protected $table = 'post_likes';
    public $timestamps = false;

    public function users(){
        return $this->hasMany(User::class);
    }

    public function forumPosts(){
        return $this->hasMany(ForumPost::class);
    }
}
