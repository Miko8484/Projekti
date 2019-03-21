<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ForumPost;

class ForumTheme extends Model
{
    protected $table = 'forum_themes';

    public function forumPosts(){
        return $this->hasMany(ForumPost::class,'theme_id');
    }
}
