<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\Game;
use App\Models\GameMatch;
use App\Models\MatchParticipant;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TeamController extends Controller
{
    // Wyświetlanie drużyn użytkownika

public function index(Request $request)
{
    $user = Auth::user();

    // Pobierz drużyny, gdzie user jest członkiem LUB liderem
    $teamsQuery = Team::where(function($q) use ($user) {
        $q->whereHas('members', function($q2) use ($user) {
            $q2->where('user_id', $user->id);
        })
        ->orWhere('leader_id', $user->id);
    })
    ->with([
        'game',
        'members',
        'leader',
        'matches' => function($q) {
            $q->where('match_date', '>', now())->orderBy('match_date');
        }
    ]);

    // FILTR: nazwa drużyny
    if ($request->filled('search')) {
        $teamsQuery->where('name', 'like', '%' . $request->search . '%');
    }

    // FILTR: gra
    if ($request->filled('game')) {
        $teamsQuery->where('game_id', $request->game);
    }

    // FILTR: rola
    if ($request->filled('role')) {
        if ($request->role === 'leader') {
            $teamsQuery->where('leader_id', $user->id);
        } elseif ($request->role === 'member') {
            $teamsQuery->whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('leader_id', '!=', $user->id);
        }
    }

    $teams = $teamsQuery->get();

    // Pobierz wszystkie gry do filtrów
    $games = Game::all();

    return view('Teams.index', compact('teams', 'games'));
}

    // Szczegóły drużyny
  
public function details($id, Request $request)
{
    $team = Team::with([
        'game',
        'members',
        'leader',
        'matches' => function($q) {
            $q->orderBy('match_date', 'desc');
        }
    ])->findOrFail($id);

    // Pobierz ID meczów, w których team brał udział
    $matchIds = MatchParticipant::where('team_id', $team->id)->pluck('match_id')->unique();

    // Pobierz rozegrane mecze tej drużyny
    $matches = GameMatch::whereIn('id', $matchIds)
        ->where('status', 'played')
        ->orderBy('match_date', 'desc')
        ->get();

    $totalMatches = $matches->count();

    $wins = 0;
    $losses = 0;
    foreach ($matches as $match) {
        $teamParticipants = MatchParticipant::where('match_id', $match->id)
            ->where('team_id', $team->id)
            ->get();

        if ($teamParticipants->count() > 0 && $teamParticipants->every(fn($p) => $p->is_winner == 1)) {
            $wins++;
        } else {
            $losses++;
        }
    }

    // Zbierz wszystkich członków (members + leader, bez duplikatów)
    $allMembers = $team->members;
    if (!$allMembers->contains('id', $team->leader_id)) {
        $allMembers = $allMembers->push($team->leader);
    }

    // FILTR: wyszukiwanie gracza po nicku
    if ($request->filled('member_search')) {
        $search = mb_strtolower($request->member_search);
        $allMembers = $allMembers->filter(function($member) use ($search) {
            return mb_strpos(mb_strtolower($member->username), $search) !== false;
        });
    }

    $winRate = $totalMatches > 0 ? round(($wins / $totalMatches) * 100) : 0;

    // Ostatnie 5 meczów (mogą być nie tylko rozegrane)
    $recentMatches = GameMatch::whereIn('id', $matchIds)
        ->orderBy('match_date', 'desc')
        ->take(5)
        ->get();

    // Najbliższy mecz (status nie musi być 'played')
    $nextMatch = GameMatch::whereIn('id', $matchIds)
        ->where('match_date', '>', now())
        ->orderBy('match_date')
        ->first();

    return view('Teams.details', compact(
        'team', 'totalMatches', 'wins', 'losses', 'winRate', 'recentMatches', 'nextMatch', 'allMembers'
    ));
}

    // Opuszczanie drużyny przez członka
    public function leave($id)
    {
        $user = Auth::user();
        $team = Team::findOrFail($id);

        // Nie pozwól liderowi opuścić własnej drużyny
        if ($team->leader_id === $user->id) {
            return back()->with('status', 'Lider nie może opuścić własnej drużyny!');
        }

        $team->members()->detach($user->id);

        return back()->with('status', 'Opuściłeś drużynę.');
    }
    // Usuwanie drużyny przez lidera
    public function delete($id)
{
    $team = Team::findOrFail($id);

    if ($team->leader_id !== Auth::id()) {
        return back()->with('status', 'Only the leader can delete the team!');
    }

    // Usuń powiązanych członków
    $team->members()->detach();

    // Usuń mecze (jeśli chcesz)
    $team->matches()->delete();

    // Usuń drużynę
    $team->delete();

    return redirect()->route('teams.my')->with('status', 'Team deleted!');
}
public function create()
{
    $user = Auth::user();

    // Pobierz ID gier, w których user już jest w drużynie (jako członek lub lider)
    $joinedGameIds = Team::whereHas('members', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })
    ->orWhere('leader_id', $user->id)
    ->pluck('game_id')
    ->unique()
    ->toArray();

    // Gry, do których user NIE należy w żadnej drużynie
    $availableGames = Game::whereNotIn('id', $joinedGameIds)->get();

    return view('Teams.create', compact('availableGames'));
}

public function store(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'name' => 'required|string|max:255|unique:teams,name',
        'game_id' => 'required|exists:games,id',
    ]);

    // Sprawdź, czy user już jest w drużynie o tej grze
    $alreadyInTeam = Team::where('game_id', $request->game_id)
        ->where(function($q) use ($user) {
            $q->whereHas('members', function($q2) use ($user) {
                $q2->where('user_id', $user->id);
            })
            ->orWhere('leader_id', $user->id);
        })
        ->exists();

    if ($alreadyInTeam) {
        return redirect()->back()->with('error', 'Już należysz do drużyny w tej grze!');
    }

    // Tworzenie drużyny
    $team = Team::create([
        'name' => $request->name,
        'game_id' => $request->game_id,
        'leader_id' => $user->id,
    ]);

    // Dodaj lidera jako członka
    $team->members()->attach($user->id);

    return redirect()->route('teams.index')->with('success', 'Drużyna została utworzona!');
}

public function addMatch(Request $request, $teamId)
{
    $user = Auth::user();
    $team = Team::with('members')->findOrFail($teamId);

    // Tylko lider może dodać mecz
    if ($team->leader_id !== $user->id) {
        return back()->with('error', 'Tylko lider może dodawać mecze!');
    }

    $request->validate([
        'match_date' => 'required|date',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:2000',
        'opponent_name' => 'nullable|string|max:255',
    ]);

    DB::transaction(function () use ($request, $team,$user) {
        // Utwórz mecz
      $match = GameMatch::create([
    'game_id' => $team->game_id,
    'match_date' => $request->match_date,
    'status' => 'scheduled',
    'title' => $request->title,
    'description' => $request->description,
    'creator_id' => $user->id,
]);

        // Dodaj wszystkich członków drużyny jako uczestników meczu
        foreach ($team->members as $member) {
            MatchParticipant::create([
                'match_id' => $match->id,
                'user_id' => $member->id,
                'team_id' => $team->id,
                'is_winner' => null,
            ]);
        }
        // Dodaj lidera, jeśli nie jest w members (na wszelki wypadek)
        if (!$team->members->contains('id', $team->leader_id)) {
            MatchParticipant::create([
                'match_id' => $match->id,
                'user_id' => $team->leader_id,
                'team_id' => $team->id,
                'is_winner' => null,
            ]);
        }
    });

    return back()->with('success', 'Mecz został dodany i przypisany wszystkim członkom drużyny!');
}



public function removeMember(Request $request, $teamId, $userId)
{
    $team = Team::findOrFail($teamId);

    // Tylko lider może usuwać członków
    if ($team->leader_id !== Auth::id()) {
        return back()->with('error', 'Tylko lider może usuwać członków!');
    }

    // Nie pozwól usunąć siebie (lidera)
    if ($userId == $team->leader_id) {
        return back()->with('error', 'Nie możesz usunąć siebie jako lidera!');
    }

    // Usuń powiązania z match_participants gdzie is_winner jest NULL
    MatchParticipant::where('user_id', $userId)
        ->where('team_id', $teamId)
        ->whereNull('is_winner')
        ->delete();

    // Usuń członka z drużyny
    $team->members()->detach($userId);

    return back()->with('success', 'Członek został usunięty z drużyny i z powiązanych meczów.');
}
}