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


public function index(Request $request)
{
    $user = Auth::user();


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
  
    if ($request->filled('search')) {
        $teamsQuery->where('name', 'like', '%' . $request->search . '%');
    }
  
    if ($request->filled('game')) {
        $teamsQuery->where('game_id', $request->game);
    }
    
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
    $games = Game::all();
    return view('Teams.index', compact('teams', 'games'));
}

  
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
    $matchIds = MatchParticipant::where('team_id', $team->id)->pluck('match_id')->unique();
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
    $allMembers = $team->members;
    if (!$allMembers->contains('id', $team->leader_id)) {
        $allMembers = $allMembers->push($team->leader);
    }
    if ($request->filled('member_search')) {
        $search = mb_strtolower($request->member_search);
        $allMembers = $allMembers->filter(function($member) use ($search) {
            return mb_strpos(mb_strtolower($member->username), $search) !== false;
        });
    }
  $winRate = $totalMatches > 0 ? round(($wins / $totalMatches) * 100) : 0;
    $recentMatches = GameMatch::whereIn('id', $matchIds)
        ->orderBy('match_date', 'desc')
        ->take(5)
        ->get();
    $nextMatch = GameMatch::whereIn('id', $matchIds)
        ->where('match_date', '>', now())
        ->orderBy('match_date')
        ->first();
    return view('Teams.details', compact(
        'team', 'totalMatches', 'wins', 'losses', 'winRate', 'recentMatches', 'nextMatch', 'allMembers'
    ));
}

  
    public function leave($id)
    {
        $user = Auth::user();
        $team = Team::findOrFail($id);

    
        if ($team->leader_id === $user->id) {
            return back()->with('status', 'Lider nie może opuścić własnej drużyny!');
        }

        $team->members()->detach($user->id);

        return back()->with('status', 'Opuściłeś drużynę.');
    }

    public function delete($id)
{
    $team = Team::findOrFail($id);

    if ($team->leader_id !== Auth::id()) {
        return back()->with('status', 'Tylko leader moze usunac drużynę!');
    }

   
    $team->members()->detach();


    $team->matches()->delete();


    $team->delete();

    return redirect()->route('teams.my')->with('status', 'Team usuniet !');
}
public function create()
{
    $user = Auth::user();

   
    $joinedGameIds = Team::whereHas('members', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })
    ->orWhere('leader_id', $user->id)
    ->pluck('game_id')
    ->unique()
    ->toArray();

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


    $team = Team::create([
        'name' => $request->name,
        'game_id' => $request->game_id,
        'leader_id' => $user->id,
    ]);

 
    $team->members()->attach($user->id);

    return redirect()->route('teams.index')->with('success', 'Drużyna została utworzona!');
}

public function addMatch(Request $request, $teamId)
{
    $user = Auth::user();
    $team = Team::with('members')->findOrFail($teamId);


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
  
      $match = GameMatch::create([
    'game_id' => $team->game_id,
    'match_date' => $request->match_date,
    'status' => 'scheduled',
    'title' => $request->title,
    'description' => $request->description,
    'creator_id' => $user->id,
]);

        
        foreach ($team->members as $member) {
            MatchParticipant::create([
                'match_id' => $match->id,
                'user_id' => $member->id,
                'team_id' => $team->id,
                'is_winner' => null,
            ]);
        }
       
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

  
    if ($team->leader_id !== Auth::id()) {
        return back()->with('error', 'Tylko lider może usuwać członków!');
    }

 
    if ($userId == $team->leader_id) {
        return back()->with('error', 'Nie możesz usunąć siebie jako lidera!');
    }

    
    MatchParticipant::where('user_id', $userId)
        ->where('team_id', $teamId)
        ->whereNull('is_winner')
        ->delete();

   
    $team->members()->detach($userId);

    return back()->with('success', 'Członek został usunięty z drużyny i z powiązanych meczów.');
}
}