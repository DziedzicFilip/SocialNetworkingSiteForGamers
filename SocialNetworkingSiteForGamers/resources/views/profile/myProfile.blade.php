
@extends('main')

@section('title', 'My Profile')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .profile-header {
        background: #23272f;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.18);
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
        color: #fff;
        display: flex;
        align-items: center;
    }
    .profile-avatar {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #4f8cff;
        margin-right: 2rem;
        background: #181b20;
    }
    .profile-username {
        font-size: 2rem;
        font-weight: bold;
        color: #4f8cff;
        margin-bottom: 0.5rem;
    }
    .profile-bio {
        font-size: 1.1rem;
        color: #b0b8c1;
    }
    .profile-stats-card {
        background: #23272f;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        padding: 1.5rem 1rem;
        color: #fff;
        margin-bottom: 1.5rem;
    }
    .profile-stats-card h5 {
        color: #4f8cff;
        margin-bottom: 1rem;
    }
    .profile-games-list li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
  <div class="profile-header">
    <img src="{{ asset($user->profile_image ?? 'IMG/default-avatar.jpg') }}" alt="Your Avatar" class="profile-avatar">
    <div>
        <div class="profile-username">{{ $user->username }}</div>
<div class="profile-bio">{{ $user->bio }}</div>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary mt-3">Edit Profile</a>
    </div>
  </div>

    <div class="row">
        <div class="col-md-6">
            <div class="profile-stats-card">
                <h5>General Statistics</h5>
                <ul class="mb-0">
                    <li>Total Matches: <strong>{{ $totalMatches }}</strong></li>
                    <li>Wins: <strong>{{ $wins }}</strong></li>
                    <li>Losses: <strong>{{ $losses }}</strong></li>
                    <li>Win Rate: <strong>{{ $winRate }}%</strong></li>
                    <li>Teams: <strong>{{ $teamsCount }}</strong></li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="profile-stats-card">
                <h5>Games Played</h5>
                <ul class="profile-games-list mb-0">
                    @foreach($games as $game)
                        <li>{{ $game->name }}: <strong>{{ $gamesPlayed[$game->id] ?? 0 }}</strong> matches</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

   <div class="profile-stats-card mt-4">
    <h5>Recent Matches</h5>
    <ul class="mb-0">
        @foreach($recentMatches as $match)
            @php
                // Pobierz uczestnika tego meczu dla użytkownika
                $participant = \App\Models\MatchParticipant::where('match_id', $match->id)
                    ->where('user_id', $user->id)
                    ->first();
                $isWin = $participant && $participant->is_winner;
            @endphp
            <li>
                {{ $match->match_date }}:
                @if($match->status === 'played')
                    @if($isWin)
                        <span class="text-success">Win</span>
                    @else
                        <span class="text-danger">Loss</span>
                    @endif
                @else
                    <span class="text-warning">Not played</span>
                @endif
                (Game: {{ $match->game->name ?? 'Unknown' }})
            </li>
        @endforeach
    </ul>
</div>
@endsection