<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\Game;
use \App\Models\Post;
use App\Models\PostParticipant;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $teams = Team::all();
        $leaderTeams = collect();
        if ($user) {
            $leaderTeams = Team::where('leader_id', $user->id)->get();
        }
         $games = Game::where('visible', 1)->get();
        $userTeams = collect();
if ($user) {
    $userTeams = Team::with('game')
    ->whereHas('members', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->get();
}
       $posts = Post::with(['game', 'user', 'team'])
    ->where('visible', 1)
    ->when($request->filter_title, fn($q) =>
        $q->where('title', 'like', '%' . $request->filter_title . '%'))
    ->when($request->filter_game, fn($q) =>
        $q->where('game_id', $request->filter_game))
    ->when($request->filter_team, fn($q) =>
        $q->where('team_id', $request->filter_team))
    ->when($request->filter_type, fn($q) =>
        $q->where('type', $request->filter_type))
    ->when($request->filter_date, fn($q) =>
        $q->whereDate('created_at', $request->filter_date))
    ->when($request->filter_user, function($q) use ($request) {
        $q->whereHas('user', function($q2) use ($request) {
            $q2->where('username', 'like', '%' . $request->filter_user . '%');
        });
    })
    ->orderBy('created_at', $request->filter_sort === 'asc' ? 'asc' : 'desc')
    ->get();
        $userId = Auth::id();
foreach ($posts as $post) {
    $post->already_applied = PostParticipant::where('post_id', $post->id)
        ->where('user_id', $userId)
        ->exists();
}
$pendingRequests = PostParticipant::with(['user', 'post'])
    ->whereHas('post', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })
    ->where('status', 'pending')
    ->get();
   $participantPostIds = PostParticipant::where('user_id', $user->id)
        ->where('status', 'accepted')
        ->pluck('post_id')
        ->toArray();
     $upcomingEvents = Post::where(function($q) use ($user, $participantPostIds) {
        $q->where('user_id', $user->id)
          ->orWhereIn('id', $participantPostIds);
    })
    ->whereColumn('current_players', 'max_players')
    ->where('play_time', '>', now()) 
    ->orderBy('play_time')
    ->get();


     return view('home.index', compact('leaderTeams', 'games','teams' ,'posts','pendingRequests','upcomingEvents','userTeams'));
     
    }

    
}
