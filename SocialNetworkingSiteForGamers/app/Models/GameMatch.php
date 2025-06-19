<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    protected $table = 'matches';
    public $timestamps = false;

    protected $fillable = [
    'game_id', 'match_date', 'winner_team_id', 'status', 'is_played',
    'title', 'description', 'creator_id',
];

public function creator()
{
    return $this->belongsTo(User::class, 'creator_id');
}
    protected $casts = [
        'match_date' => 'datetime', // Tutaj dodana linia
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function winnerTeam()
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    public function winnerUser()
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'match_participants', 'match_id', 'user_id');
    }
    public function matchParticipants()
{
    return $this->hasMany(MatchParticipant::class, 'match_id');
}
}