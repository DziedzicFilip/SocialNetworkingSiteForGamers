<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    public $timestamps = false;

    protected $fillable = [
        'title','user_id', 'game_id', 'content', 'created_at', 'type', 'max_players', 'current_players', 'visible','play_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function participants()
    {
        return $this->hasMany(PostParticipant::class, 'post_id');
    }
    public function team()
{
    return $this->belongsTo(\App\Models\Team::class, 'team_id');
}
}