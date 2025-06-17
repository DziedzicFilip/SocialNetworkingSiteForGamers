<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\GameMatch;
use App\Models\Game;
use App\Models\User;
use App\Models\Team;
use App\Models\MatchParticipant;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
public function update(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'bio' => 'nullable|string|max:255',
        'profile_image' => 'nullable|image|max:2048',
    ]);

    $user->username = $validated['username'];
    $user->email = $validated['email'];
    $user->bio = $validated['bio'] ?? $user->bio;

    // Obsługa uploadu avatara
    if ($request->hasFile('profile_image')) {
    $file = $request->file('profile_image');
    $filename = 'avatar_' . $user->id . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('IMG'), $filename);
    $user->profile_image = 'IMG/' . $filename;
}

    $user->save();

    return back()->with('status', 'profile-updated');
}


public function updatePassword(Request $request)
{
    //dd('działa', $request->all());
    $user = $request->user();

    $request->validate([
        'current_password' => ['required'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    if (!Hash::check($request->current_password, $user->password_hash)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    $user->password_hash = Hash::make($request->password);
    $user->save();

    return back()->with('status', 'password-updated');
}
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }




public function myProfile()
{
    $user = Auth::user();

    // Pobierz wszystkie mecze, w których użytkownik brał udział
    $matchIds = MatchParticipant::where('user_id', $user->id)->pluck('match_id');
    $matches = GameMatch::whereIn('id', $matchIds)->with('game')->get();

    // ID drużyn użytkownika
    $userTeamIds = $user->teams->pluck('teams.id')->toArray();

    // Wygrane mecze solo (gdzie user wygrał indywidualnie)
    $soloWins = GameMatch::whereIn('id', $matchIds)
        ->where('winner_user_id', $user->id)
        ->count();

    // Wygrane mecze drużynowe (gdzie wygrała drużyna użytkownika)
    $teamWins = GameMatch::whereIn('id', $matchIds)
        ->whereIn('winner_team_id', $userTeamIds)
        ->count();

    $wins = $soloWins + $teamWins;

    // Ogólna statystyka
    $totalMatches = $matches->count();
    $losses = $totalMatches - $wins;
    $winRate = $totalMatches > 0 ? round(($wins / $totalMatches) * 100) : 0;

    // Gry, w których użytkownik brał udział
    $gamesPlayed = $matches->groupBy('game_id')->map->count();
    $games = Game::whereIn('id', $gamesPlayed->keys())->get();

    // Ostatnie mecze
    $recentMatches = $matches->sortByDesc('match_date')->take(5);

    // Liczba drużyn użytkownika
    $teamsCount = $user->teams->count();

    return view('profile.myProfile', compact(
        'user', 'teamsCount', 'totalMatches', 'wins', 'losses', 'winRate', 'gamesPlayed', 'games', 'recentMatches'
    ));
}

      
}
