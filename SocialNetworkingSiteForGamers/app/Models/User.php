<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    public $timestamps = false;

   protected $fillable = [
    'username', 'email', 'password_hash', 'created_at', 'profile_image', 'bio', 'role'
];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
            ->withPivot('role');
    }

    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function postParticipants()
    {
        return $this->hasMany(PostParticipant::class, 'user_id');
    }

    public function matchesWon()
    {
        return $this->hasMany(GameMatch::class, 'winner_user_id');
    }
    public function getAuthPassword()
{
    return $this->password_hash;
}

}