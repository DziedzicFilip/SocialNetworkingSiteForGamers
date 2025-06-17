<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
           'game_id' => 'nullable|exists:games,id',
            'type' => 'required|in:discussion,casual,team',
            'max_players' => 'nullable|integer|min:1',
            'play_time' => 'nullable|date',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->game_id = $validated['game_id'] ?? null;
        $post->type = $validated['type'];
        $post->created_at = now();
        $post->visible = 1;
        $post->current_players = 0;
        $post->max_players = $validated['max_players'] ?? null;
        $post->play_time = $validated['play_time'] ?? null;
        $post->team_id = $validated['team_id'] ?? null;
        $post->save();

       return redirect()->back()->with('status', 'Post added!');
    }

    public function apply($id)
    {
        // Tutaj logika zgłoszenia się do posta (np. zapis do post_participants)
        // ...
        return back()->with('status', 'Zgłoszenie wysłane!');
    }
}