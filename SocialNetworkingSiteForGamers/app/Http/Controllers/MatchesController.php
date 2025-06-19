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

    $query = GameMatch::with('game')
        ->whereHas('matchParticipants', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

    // Filtrowanie po tytule
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // Filtrowanie po grze
    if ($request->filled('game_id')) {
        $query->where('game_id', $request->game_id);
    }

    // Filtrowanie po statusie
    if ($request->filled('status')) {
        if ($request->status === 'upcoming') {
            $query->where('status', '!=', 'played')->where('status', '!=', 'canceled');
        } else {
            $query->where('status', $request->status);
        }
    }
    $query->where('status', '!=', 'canceled'); // Wyklucz mecze anulowane

    $filteredMatches = $query->orderByDesc('match_date')->get();
    $games = Game::all();

    // Kalendarz
    $calendarEvents = $filteredMatches->map(function($match) {
        return [
            'title' => ($match->game->name ?? '') . '<br>' . e($match->title ?? ''),
            'start' => $match->match_date,
            'url' => route('matches.show', $match->id),
        ];
    });

    return view('Matches.index', compact('filteredMatches', 'games', 'calendarEvents'));
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
    $match = GameMatch::with(['matchParticipants'])->findOrFail($id);

    // Sprawdź, czy użytkownik jest właścicielem meczu (liderem teamu lub creator_id)
    if (
        Auth::id() !== optional($match->team)->leader_id &&
        Auth::id() !== $match->creator_id
    ) {
        abort(403, 'Brak uprawnień do edycji tego meczu.');
    }

    // Walidacja
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:2000',
        'match_date' => 'required|date',
        'status' => 'required|in:played,canceled',
        'result' => 'required|in:win,lose',
    ]);

    // Aktualizacja danych meczu
    $match->title = $validated['title'];
    $match->description = $validated['description'];
    $match->match_date = $validated['match_date'];
    $match->status = $validated['status'];
    $match->save();

    // Aktualizacja wyniku dla uczestników
    foreach ($match->matchParticipants as $participant) {
        if ($match->status === 'played') {
            $participant->is_winner = ($validated['result'] === 'win') ? 1 : 0;
        } else {
            $participant->is_winner = null;
        }
        $participant->save();
    }

    return redirect()->route('matches.show', $match->id)
        ->with('success', 'Zmiany zostały zapisane.');
}

}