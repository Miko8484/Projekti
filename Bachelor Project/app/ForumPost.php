<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\ForumTheme;
use App\ForumComment;
use App\PostLike;

class ForumPost extends Model
{
    protected $table = 'forum_posts';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function forumTheme(){
        return $this->belongsTo(ForumTheme::class,'theme_id');
    }

    public function forumComments(){
        return $this->hasMany(ForumComment::class);
    }

    public function postLikes(){
        return $this->hasMany(PostLike::class);
    }
}
