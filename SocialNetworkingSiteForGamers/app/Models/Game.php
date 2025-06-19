<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';
    public $timestamps = false;

    protected $fillable = [
        'name','image'
    ];

    public function teams()
    {
        return $this->hasMany(Team::class, 'game_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'game_id');
    }

    public function matches()
    {
        return $this->hasMany(GameMatch::class, 'game_id');
    }
}