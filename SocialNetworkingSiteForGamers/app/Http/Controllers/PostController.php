<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostParticipant;
use App\Models\TeamMember;
use App\Models\Team;
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
    $post = Post::findOrFail($id);

    // Sprawdź, czy user już jest w teamie tej gry
    $userTeams = Team::whereHas('members', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->where('game_id', $post->game_id)->count();

    // Sprawdź, czy user jest liderem teamu tej gry
    $isLeader = Team::where('game_id', $post->game_id)
        ->where('leader_id', $user->id)
        ->exists();

    if ($isLeader) {
        return back()->with('status', 'Nie możesz dołączyć do innej drużyny w tej grze, bo jesteś liderem własnej!');
    }
    if ($userTeams > 0) {
        return back()->with('status', 'Należysz już do drużyny w tej grze!');
    }

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
    $post = $request->post;
    $userId = $request->user_id;

    // Sprawdź, czy user już jest w teamie tej gry
    $userTeams = Team::whereHas('members', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })->where('game_id', $post->game_id)->count();

    if ($userTeams > 0) {
        // Odrzuć akceptację, bo user już jest w teamie tej gry
        return back()->with('status', 'Too late – user already joined another team in this game.');
    }
    $isLeader = Team::where('game_id', $post->game_id)
    ->where('leader_id', $userId)
    ->exists();

if ($isLeader) {
    return back()->with('status', 'Nie możesz dołączyć do innej drużyny w tej grze, bo jesteś liderem własnej!');
}

    // Akceptuj zgłoszenie
    $request->status = 'accepted';
    $request->save();

    $post->current_players = $post->current_players + 1;
    if ($post->max_players && $post->current_players >= $post->max_players) {
        $post->visible = 0;
    }
    $post->save();

    // Dodaj do teamu
    if ($post->type === 'team' && $post->team_id) {
        TeamMember::firstOrCreate([
            'team_id' => $post->team_id,
            'user_id' => $userId,
        ]);
    }

    // Oznacz inne zgłoszenia tego usera do innych teamów w tej grze jako "expired"
    PostParticipant::where('user_id', $userId)
        ->whereHas('post', function($q) use ($post) {
            $q->where('game_id', $post->game_id)
              ->where('id', '!=', $post->id);
        })
        ->where('status', 'pending')
        ->update(['status' => 'expired']);

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