<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;

class TeamController extends Controller
{
    // Wyświetlanie drużyn użytkownika
public function index()
{
    $user = Auth::user();

    // Pobierz drużyny, gdzie user jest członkiem LUB liderem
   $teams = Team::where(function($q) use ($user) {
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
    ])
    ->get();

    return view('Teams.index', compact('teams'));
}

    // Szczegóły drużyny
    public function details($id)
{
    $team = Team::with([
        'game',
        'members',
        'leader',
        'matches' => function($q) {
            $q->orderBy('match_date', 'desc');
        }
    ])->findOrFail($id);

    // Statystyki
    $totalMatches = $team->matches->count();
    $wins = $team->matches->where('winner_team_id', $team->id)->count();
    $losses = $totalMatches - $wins;
    $winRate = $totalMatches > 0 ? round(($wins / $totalMatches) * 100) : 0;

    // Ostatnie 5 meczów
    $recentMatches = $team->matches->take(5);

    // Najbliższy mecz
    $nextMatch = $team->matches->where('match_date', '>', now())->sortBy('match_date')->first();

    return view('Teams.details', compact(
        'team', 'totalMatches', 'wins', 'losses', 'winRate', 'recentMatches', 'nextMatch'
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
}