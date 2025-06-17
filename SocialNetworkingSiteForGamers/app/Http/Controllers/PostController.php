<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostParticipant;
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
    $user = Auth::user();

    $exists = PostParticipant::where('post_id', $id)
        ->where('user_id', $user->id)
        ->exists();

    if ($exists) {
        return back()->with('status', 'Już zgłosiłeś się do tego wydarzenia!');
    }

    PostParticipant::create([
        'post_id' => $id,
        'user_id' => $user->id,
        'status' => 'pending',
        'joined_at' => now(),
    ]);

    return back()->with('status', 'Zgłoszenie wysłane!');
}
    public function acceptRequest($id)
{
    $request = PostParticipant::findOrFail($id);
    $request->status = 'accepted';
    $request->save();

    // Zwiększ current_players w poście
    $post = $request->post;
    $post->current_players = $post->current_players + 1;

    // Jeśli osiągnięto limit graczy, ukryj post
    if ($post->max_players && $post->current_players >= $post->max_players) {
        $post->visible = 0;
    }

    $post->save();

    return back()->with('status', 'Request accepted!');
}

public function rejectRequest($id)
{
    $request = PostParticipant::findOrFail($id);
    $request->status = 'rejected';
    $request->save();

    return back()->with('status', 'Request rejected!');
}
}