<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostParticipant;
use App\Models\TeamMember;
use App\Models\Team;
use App\Models\GameMatch;
use App\Models\Game;
class PostController extends Controller

{
    public function store(Request $request)
{
    if ($request->type === 'team') {
    $userTeam = \App\Models\Team::where('leader_id', Auth::id())->first();
    if (!$userTeam) {
        return back()->withErrors(['type' => 'Nie możesz dodać posta rekrutacyjnego, jeśli nie jesteś liderem żadnej drużyny.'])->withInput();
    }
}
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string|max:500',
        'game_id' => 'nullable|exists:games,id',
        'type' => 'required|in:discussion,casual,team',
        'max_players' => 'nullable|integer|min:1',
        'play_time' => 'nullable|date|after:now',
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

    // Dodaj mecz jeśli to ogłoszenie o grę
   if (in_array($post->type, ['casual', 'team']) && $post->game_id && $post->play_time) {
    GameMatch::create([
        'game_id' => $post->game_id,
        'match_date' => $post->play_time,
        'winner_team_id' => null,
        'status' => 'scheduled',
        'title' => $post->title,
        'description' => $post->content,
        'creator_id' => $post->user_id,
    ]);
}

    return redirect()->back()->with('status', 'Post added!');
}

  public function apply($id)
{
    $user = Auth::user();
    $post = Post::findOrFail($id);

    // Tylko dla postów typu 'team' blokuj dołączanie, jeśli user jest już w teamie tej gry
    if ($post->type === 'team') {
        $userTeams = Team::whereHas('members', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('game_id', $post->game_id)->count();

        $isLeader = Team::where('game_id', $post->game_id)
            ->where('leader_id', $user->id)
            ->exists();

        if ($isLeader) {
            return back()->with('status', 'Nie możesz dołączyć do innej drużyny w tej grze, bo jesteś liderem własnej!');
        }
        if ($userTeams > 0) {
            return back()->with('status', 'Należysz już do drużyny w tej grze!');
        }
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
    $request = \App\Models\PostParticipant::findOrFail($id);
    $post = $request->post;
    $userId = $request->user_id;

   if ($post->type === 'team') {
    // Sprawdź, czy user już jest w teamie tej gry
    $userTeams = Team::whereHas('members', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })->where('game_id', $post->game_id)->count();

    // Sprawdź, czy user jest liderem teamu tej gry
    $isLeader = Team::where('game_id', $post->game_id)
        ->where('leader_id', $userId)
        ->exists();

    if ($isLeader) {
        return back()->with('status', 'Nie możesz dołączyć do innej drużyny w tej grze, bo jesteś liderem własnej!');
    }
    if ($userTeams > 0) {
        return back()->with('status', 'Too late – user already joined another team in this game.');
    }
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
        \App\Models\TeamMember::firstOrCreate([
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

    // Dodaj lidera i zaakceptowanego gracza do match_participants
 if ($post->type === 'casual') {
    $match = \App\Models\GameMatch::where('game_id', $post->game_id)
        ->where('match_date', $post->play_time)
        ->first();

    if ($match) {
        // Twórca posta (organizator casual gry)
        \App\Models\MatchParticipant::firstOrCreate([
            'match_id' => $match->id,
            'user_id' => $post->user_id,
        ]);
        // Akceptowany gracz
        \App\Models\MatchParticipant::firstOrCreate([
            'match_id' => $match->id,
            'user_id' => $userId,
        ]);
    }
}

    return back()->with('status', 'Request accepted!');
}
public function rejectRequest($id)
{
    $request = PostParticipant::findOrFail($id);
    $request->status = 'rejected';
    $request->save();

    return back()->with('status', 'Request rejected!');
}

public function myPosts(Request $request)
{
    $user = Auth::user();

    $query = Post::where('user_id', $user->id);

    // Filtry
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }
    if ($request->filled('game_id')) {
        $query->where('game_id', $request->game_id);
    }
 if ($request->filled('visible')) {
    $query->where('visible', $request->visible);
}

    $posts = $query->orderByDesc('created_at')->paginate(10);
    $games = \App\Models\Game::all();

    return view('Posts.index', compact('posts', 'games'));
}

public function edit($id)
{
    $post = Post::findOrFail($id);
    if (Auth::id() !== $post->user_id) {
    abort(403, 'Brak dostępu');
}
    // lub sprawdź Auth::id() === $post->user_id
    $games = \App\Models\Game::all();
    $teams = \App\Models\Team::where('game_id', $post->game_id)->get();
    return view('Posts.edit', compact('post', 'games', 'teams'));
}

public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);
    if (Auth::id() !== $post->user_id) {
        abort(403, 'Brak dostępu');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
       'content' => 'required|string|max:500',
        'game_id' => 'nullable|exists:games,id',
        'type' => 'required|in:discussion,casual,team',
        'max_players' => 'nullable|integer|min:1',
        'play_time' => 'nullable|date|after:now',
        'team_id' => 'nullable|exists:teams,id',
        'visible' => 'required|in:0,1',
    ]);

    unset($validated['type']);

    if ($post->type !== 'discussion') {
        $validated['visible'] = 0;
        $validated['max_players'] = $validated['max_players'] ?? null;
        $validated['play_time'] = $validated['play_time'] ?? null;
        $validated['team_id'] = $validated['team_id'] ?? null;
    }

    $post->fill($validated);
    $post->save();

    return redirect()->route('posts.my')->with('status', 'Post zaktualizowany!');
}
}