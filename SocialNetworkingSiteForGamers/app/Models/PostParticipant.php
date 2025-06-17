<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostParticipant extends Model
{
    protected $table = 'post_participants';
    public $timestamps = false;

    protected $fillable = [
        'post_id', 'user_id', 'status', 'joined_at'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}