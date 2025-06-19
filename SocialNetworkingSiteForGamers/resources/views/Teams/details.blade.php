
@extends('main')

@section('title', 'Team Details')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .player-card {
        background: #23272f;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.12);
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
        color: #fff;
        transition: box-shadow 0.2s;
    }
    .player-card:hover {
        box-shadow: 0 4px 24px rgba(79,140,255,0.25);
    }
    .profile-btn {
        margin-left: 1rem;
    }
    .profile-modal .modal-content {
        background: #181b20;
        color: #fff;
        border-radius: 14px;
    }
    .profile-modal .modal-header {
        border-bottom: 1px solid #23272f;
    }
    .profile-modal .modal-footer {
        border-top: 1px solid #23272f;
    }
    .profile-avatar {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #4f8cff;
        margin-right: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary">
        {{ $team->name }}
        <small class="text-muted">({{ $team->game->name ?? '-' }})</small>
    </h2>
    <div class="row mb-4">
        <div class="col-md-4">
            @if($team->game && $team->game->logo_url)
                <img src="{{ asset($team->game->logo_url) }}" alt="{{ $team->game->name }}" class="img-fluid rounded shadow">
            @endif
        </div>
        <div class="col-md-8">
            <h5>Description</h5>
            <p>{{ $team->description ?? 'No description.' }}</p>
            <h5>Statistics</h5>
            <ul>
                <li>Matches played: <strong>{{ $totalMatches }}</strong></li>
                <li>Wins: <strong>{{ $wins }}</strong></li>
                <li>Losses: <strong>{{ $losses }}</strong></li>
                <li>Win rate: <strong>{{ $winRate }}%</strong></li>
            </ul>
           <h5>Recent Matches</h5>
<ul>
@foreach($recentMatches as $match)
    @php
        $participants = \App\Models\MatchParticipant::where('match_id', $match->id)->get();
        $opponent = $participants->firstWhere('team_id', '!=', $team->id);
        
        // Czy nasz team wygrał?
        $ourParticipant = $participants->firstWhere('team_id', $team->id);
        $isWin = $ourParticipant && $ourParticipant->is_winner;
    @endphp
    <li>
        {{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}:
        @if($match->status === 'played')
            @if($isWin)
                <span class="text-success">Win</span>
            @else
                <span class="text-danger">Loss</span>
            @endif
        @else
            <span class="text-warning">Not played</span>
        @endif
       
        ({{ $match->score ?? '-' }})
    </li>
@endforeach
</ul>
@if($team->leader_id === Auth::id())
<div class="card my-4">
    <div class="card-body">
        <h5 class="card-title">Dodaj nowy Event zespołowy</h5>
        <form method="POST" action="{{ route('teams.addMatch', $team->id) }}">
            @csrf
            <div class="mb-3">
                <label for="match_date" class="form-label">Data i godzina meczu</label>
                <input type="datetime-local" name="match_date" id="match_date" class="form-control" required>
            </div>
           
            <button type="submit" class="btn btn-success">Dodaj mecz</button>
        </form>
    </div>
</div>
@endif
            <div class="mt-3">
                <strong>Next Match:</strong>
                @if($nextMatch)
                    <span class="badge bg-success">
                        {{ $nextMatch->opponent_name ?? '-' }} - {{ \Carbon\Carbon::parse($nextMatch->match_date)->format('Y-m-d H:i') }}
                    </span>
                @else
                    <span class="text-muted">No upcoming matches</span>
                @endif
            </div>
            {{-- Opcja opuść/usuń drużynę --}}
            @if($team->leader_id === Auth::id())
                <form method="POST" action="{{ route('teams.delete', $team->id) }}" class="mt-4 d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this team? All matches and members will be removed!');">
                        Delete Team
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('teams.leave', $team->id) }}" class="mt-4 d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to leave this team?');">
                        Leave Team
                    </button>
                </form>
            @endif
        </div>
    </div>

    <h4 class="mb-3 mt-5 text-primary">Team Members</h4>
    <div class="row">
       @php
    // Zbierz wszystkich członków (members + leader, bez duplikatów)
    $allMembers = $team->members;
    if (!$allMembers->contains('id', $team->leader_id)) {
        $allMembers = $allMembers->push($team->leader);
    }
@endphp

@foreach($allMembers as $member)
    <div class="col-md-6">
        <div class="player-card d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{ asset($member->profile_image ?? 'IMG/default-avatar.jpg') }}" alt="{{ $member->username }}" class="profile-avatar">
                <div>
                    <div class="fw-bold">{{ $member->username }}</div>
                    <div class="text-muted small">
                        Role: {{ $team->leader_id == $member->id ? 'Leader' : 'Member' }}
                    </div>
                </div>
            </div>
            <a href="{{ route('profile.show', $member->id) }}" class="btn btn-outline-primary profile-btn">View Profile</a>
        </div>
    </div>
@endforeach
    </div>

    <a href="{{ route('teams.my') }}" class="btn btn-outline-secondary mt-4">Back to My Teams</a>
</div>
@endsection