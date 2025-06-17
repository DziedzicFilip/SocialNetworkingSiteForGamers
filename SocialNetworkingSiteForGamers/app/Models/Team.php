<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';
    public $timestamps = false;

    protected $fillable = [
        'name', 'leader_id', 'game_id', 'created_at'
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id')
            ->withPivot('role');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function matchesWon()
    {
        return $this->hasMany(GameMatch::class, 'winner_team_id');
    }
}