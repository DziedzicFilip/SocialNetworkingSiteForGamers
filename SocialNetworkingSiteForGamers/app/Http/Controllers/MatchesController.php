<?php 
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GameMatch;
use App\Models\Game;
use Carbon\Carbon;
class MatchesController extends Controller {

public function index(Request $request)
{
    $user = Auth::user();

    $query = GameMatch::whereHas('participants', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->with('game');

    // Filtr gry
    if ($request->filled('game_id')) {
        $query->where('game_id', $request->game_id);
    }

    // Filtr statusu
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Wyszukiwarka po nazwie przeciwnika
    if ($request->filled('search')) {
        $query->where('opponent_name', 'like', '%' . $request->search . '%');
    }

    $matches = $query->orderBy('match_date')->get();

    // Dodaj zmienną filteredMatches (możesz ją filtrować osobno, jeśli chcesz)
    $filteredMatches = $matches;

    // Pobierz gry do selecta (jeśli chcesz dynamicznie)
    $games = Game::all();

    // Generowanie danych dla kalendarza
    $calendarEvents = $matches->map(function ($match) {
        return [
            'title' => $match->game->name ?? 'Match',
            'start' => $match->match_date->toIso8601String(),
            'url' => route('matches.show', $match->id)
        ];
    });

    return view('Matches.index', compact('matches', 'filteredMatches', 'games', 'calendarEvents'));
}
public function show($id)
{
    $match = GameMatch::with('game', 'participants')->findOrFail($id);
    return view('Matches.show', compact('match'));
}
public function cancel($id)
{
    $match = GameMatch::findOrFail($id);
    $match->status = 'canceled';
    $match->save();

    return redirect()->route('matches.index')->with('success', 'Match canceled.');
}
public function update(Request $request, $id)
{
    $match = GameMatch::with(['matchParticipants.user'])->findOrFail($id);

    $match->status = $request->input('status');
    $match->save();

    $result = $request->input('result'); // 'win' lub 'lose'

    foreach ($match->matchParticipants as $participant) {
        if ($match->status === 'played') {
            $participant->is_winner = ($result === 'win') ? 1 : 0;
        } else {
            $participant->is_winner = null; // nie rozstrzygnięto
        }
        $participant->save();
    }

    return redirect()->route('matches.show', $match->id)
        ->with('success', 'Wynik meczu został zapisany.');
}
}