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
use App\Models\TeamMember;

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
    'password' => [
        'required',
        'string',
        'min:8',
        'confirmed',
        'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
    ],
], [
    'password.regex' => 'Hasło musi zawierać co najmniej jedną dużą literę, jedną cyfrę i jeden znak specjalny.',
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

 
    $memberTeamIds = \App\Models\TeamMember::where('user_id', $user->id)->pluck('team_id')->toArray();

 
    $leaderTeamIds = \App\Models\Team::where('leader_id', $user->id)->pluck('id')->toArray();

    
    $allTeamIds = array_unique(array_merge($memberTeamIds, $leaderTeamIds));
    $teamsCount = count($allTeamIds);


$matchIds = MatchParticipant::where('user_id', $user->id)->pluck('match_id');
$matches = GameMatch::whereIn('id', $matchIds)
    ->where('status', 'played')
    ->with('game')
    ->get();


$wins = MatchParticipant::where('user_id', $user->id)
    ->whereIn('match_id', $matches->pluck('id'))
    ->where('is_winner', 1)
    ->count();

$totalMatches = $matches->count();
$losses = $totalMatches - $wins;
$winRate = $totalMatches > 0 ? round(($wins / $totalMatches) * 100) : 0;


    $gamesPlayed = $matches->groupBy('game_id')->map->count();
    $games = Game::whereIn('id', $gamesPlayed->keys())->get();

 
    $recentMatches = $matches->sortByDesc('match_date')->take(5);

  
    $teamsCount = $user->teams->count();

    return view('profile.myProfile', compact(
        'user', 'teamsCount', 'totalMatches', 'wins', 'losses', 'winRate', 'gamesPlayed', 'games', 'recentMatches'
    ));
}
public function show($id)
{
    $user = User::findOrFail($id);


$matchIds = MatchParticipant::where('user_id', $user->id)->pluck('match_id');
$matches = GameMatch::whereIn('id', $matchIds)
    ->where('status', 'played')
    ->with('game')
    ->get();

$wins = MatchParticipant::where('user_id', $user->id)
    ->whereIn('match_id', $matches->pluck('id'))
    ->where('is_winner', 1)
    ->count();

$totalMatches = $matches->count();
$losses = $totalMatches - $wins;
$winRate = $totalMatches > 0 ? round(($wins / $totalMatches) * 100) : 0;


    $teamsCount = $user->teams()->count();


    $gamesPlayed = $matches->groupBy('game_id')->map->count();
    $games = Game::whereIn('id', $gamesPlayed->keys())->get();

    $recentMatches = $matches->sortByDesc('match_date')->take(5);

    return view('profile.myProfile', compact(
        'user', 'totalMatches', 'wins', 'losses', 'winRate', 'teamsCount', 'games', 'gamesPlayed', 'recentMatches'
    ));
}
}
