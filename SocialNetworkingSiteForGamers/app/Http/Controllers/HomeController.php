<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\Game;
use \App\Models\Post;
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
         $games = Game::all();
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
        ->orderBy('created_at', $request->filter_sort === 'asc' ? 'asc' : 'desc')
        ->get();
     return view('home.index', compact('leaderTeams', 'games','teams' ,'posts'));
    }
}
