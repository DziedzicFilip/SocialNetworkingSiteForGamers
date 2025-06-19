@extends('main')

@section('title', 'Szczegóły drużyny')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
<style>
    .team-banner {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 2rem;
        background: #23272f;
    }
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
    .profile-avatar {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #4f8cff;
        margin-right: 1rem;
    }
    .calendar-mini {
        background: #23272f;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 2rem;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    {{-- Baner z logo gry --}}
    @if($team->game && $team->game->image)
        <img src="{{ asset($team->game->image) }}" alt="{{ $team->game->name }}" class="team-banner shadow">
    @endif

    <h2 class="mb-4 text-primary">
        {{ $team->name }}
        <small class="text-muted">({{ $team->game->name ?? '-' }})</small>
    </h2>

    <div class="row">
        {{-- Lewa kolumna: opis, statystyki, ostatnie mecze, członkowie --}}
        <div class="col-md-6">
            <div class="mb-4">
                <h5>Opis</h5>
                <p>{{ $team->description ?? 'Brak opisu.' }}</p>
            </div>
            <div class="mb-4">
                <h5>Statystyki</h5>
                <ul>
                    <li>Rozegrane mecze: <strong>{{ $totalMatches }}</strong></li>
                    <li>Zwycięstwa: <strong>{{ $wins }}</strong></li>
                    <li>Porażki: <strong>{{ $losses }}</strong></li>
                    <li>Procent zwycięstw: <strong>{{ $winRate }}%</strong></li>
                </ul>
            </div>
            <div class="mb-4">
                <h5>Ostatnie mecze</h5>
                <ul>
                @foreach($recentMatches as $match)
                    @php
                        $participants = \App\Models\MatchParticipant::where('match_id', $match->id)->get();
                        $opponent = $participants->firstWhere('team_id', '!=', $team->id);
                        $ourParticipant = $participants->firstWhere('team_id', $team->id);
                        $isWin = $ourParticipant && $ourParticipant->is_winner;
                    @endphp
                    <li>
                        {{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') }}:
                        @if($match->status === 'played')
                            @if($isWin)
                                <span class="text-success">Wygrana</span>
                            @else
                                <span class="text-danger">Porażka</span>
                            @endif
                        @else
                            <span class="text-warning">Nierozegrany</span>
                        @endif
                        ({{ $match->score ?? '-' }})
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="mb-4">
                <h5>Członkowie drużyny</h5>
                <div class="row">
                @php
                    $allMembers = $team->members;
                    if (!$allMembers->contains('id', $team->leader_id)) {
                        $allMembers = $allMembers->push($team->leader);
                    }
                @endphp
                @foreach($allMembers as $member)
                    <div class="col-12 mb-2">
                        <div class="player-card d-flex align-items-center justify-content-between p-2">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($member->profile_image ?? 'IMG/default-avatar.jpg') }}" alt="{{ $member->username }}" class="profile-avatar">
                                <div>
                                    <div class="fw-bold">{{ $member->username }}</div>
                                    <div class="text-muted small">
                                        Rola: {{ $team->leader_id == $member->id ? 'Lider' : 'Członek' }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('profile.show', $member->id) }}" class="btn btn-outline-primary btn-sm">Zobacz profil</a>
                                @if($team->leader_id === Auth::id() && $member->id !== $team->leader_id)
                                    <form method="POST" action="{{ route('teams.removeMember', [$team->id, $member->id]) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm ms-2" onclick="return confirm('Na pewno usunąć tego członka z drużyny?')">
                                            Usuń
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>

        {{-- Prawa kolumna: dodawanie meczu, akcje, mini-kalendarz --}}
        <div class="col-md-6">
            @if($team->leader_id === Auth::id())
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Dodaj nowy mecz zespołowy</h5>
                    <form method="POST" action="{{ route('teams.addMatch', $team->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Tytuł</label>
                            <input type="text" name="title" id="title" class="form-control" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Opis</label>
                            <textarea name="description" id="description" class="form-control" rows="3" maxlength="2000"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="match_date" class="form-label">Data i godzina</label>
                            <input type="datetime-local" name="match_date" id="match_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Dodaj</button>
                    </form>
                </div>
            </div>
            @endif

            <div class="mb-4">
                {{-- Opcja opuść/usuń drużynę --}}
                @if($team->leader_id === Auth::id())
                    <form method="POST" action="{{ route('teams.delete', $team->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 mb-2"
                            onclick="return confirm('Czy na pewno chcesz usunąć tę drużynę? Wszystkie mecze i członkowie zostaną usunięci!');">
                            Usuń drużynę
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('teams.leave', $team->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 mb-2"
                            onclick="return confirm('Czy na pewno chcesz opuścić tę drużynę?');">
                            Opuść drużynę
                        </button>
                    </form>
                @endif
                <a href="{{ route('teams.my') }}" class="btn btn-outline-secondary w-100">Powrót do moich drużyn</a>
            </div>

            
        </div>
    </div>
</div>
@endsection

@push('scripts')



@endpush