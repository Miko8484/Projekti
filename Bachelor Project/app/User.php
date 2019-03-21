<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Score;
use App\ForumPost;
use App\PostLike;
use App\CommentLike;
use App\User;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    protected $fillable = [
        'username', 'email', 'password', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'email_verified_at', 'activation_token', 'active'
    ];

    public function scores(){
        return $this->hasMany(Score::class);
    }

    public function forumPosts(){
        return $this->hasMany(ForumPost::class);
    }

    public function forumComments(){
        return $this->hasMany(ForumPost::class);
    }

    public function postLikes(){
        return $this->hasMany(PostLike::class);
    }

    public function commentLikes(){
        return $this->hasMany(CommentLike::class);
    }

    public function bets(){
        return $this->hasMany(Bet::class);
    }
}
