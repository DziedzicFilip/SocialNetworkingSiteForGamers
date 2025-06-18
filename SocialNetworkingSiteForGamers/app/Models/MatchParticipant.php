<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchParticipant extends Model
{
    protected $table = 'match_participants';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

  protected $fillable = [
    'match_id', 'user_id', 'team_id', 'is_winner'
];
    public function match()
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}