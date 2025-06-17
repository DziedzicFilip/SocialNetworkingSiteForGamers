<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    protected $table = 'matches';
    public $timestamps = false;

    protected $fillable = [
        'game_id', 'match_date', 'winner_team_id', 'winner_user_id'
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
}