@extends('main')

@section('title', 'Moje drużyny')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary">Moje drużyny</h2>
      <div class="mb-3">
        <a href="{{ route('teams.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Stwórz nową drużynę
        </a>
    </div>
    {{-- WYSZUKIWARKA I FILTRY --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Nazwa drużyny</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Wpisz nazwę drużyny">
            </div>
            <div class="col-md-3">
                <label for="game" class="form-label">Gra</label>
                <select name="game" id="game" class="form-select">
                    <option value="">Wszystkie gry</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ request('game') == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="role" class="form-label">Rola</label>
                <select name="role" id="role" class="form-select">
                    <option value="">Wszystkie role</option>
                    <option value="leader" {{ request('role') == 'leader' ? 'selected' : '' }}>Lider</option>
                    <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Członek</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Szukaj</button>
            </div>
        </div>
    </form>

    @if($teams->isEmpty())
        <div class="alert alert-info">Nie należysz do żadnej drużyny.</div>
    @else
        @foreach($teams->groupBy('game.name') as $gameName => $gameTeams)
            <div class="card mb-4">
                <div class="card-header bg-light d-flex align-items-center">
                    @if($gameTeams->first()->game && $gameTeams->first()->game->logo_url)
                        <img src="{{ asset($gameTeams->first()->game->logo_url) }}" alt="{{ $gameName }}" class="me-3" style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
                    @endif
                    <h5 class="mb-0">{{ $gameName }}</h5>
                </div>
                <div class="card-body">
                    @foreach($gameTeams as $team)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">{{ $team->name }}</h5>
                                    <div class="text-muted mb-2">{{ $team->description ?? '' }}</div>
                                    <div>
                                        @php
    $allMembers = $team->members;
    if (!$allMembers->contains('id', $team->leader_id)) {
        $allMembers = $allMembers->push($team->leader);
    }
@endphp
                                        <strong>Członkowie:</strong>
                                        @foreach($allMembers as $member)
                                            <span class="badge bg-secondary">{{ $member->username }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    @if($team->leader_id === Auth::id())
                                        <span class="badge bg-primary">Lider</span>
                                    @else
                                        <span class="badge bg-info">Członek</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Następny mecz:</strong>
                                @php
                                    // Pobierz najbliższy mecz (po dacie rosnąco)
                                    $nextMatch = $team->matches
                                        ->where('match_date', '>', now())
                                        ->sortBy('match_date')
                                        ->first();
                                @endphp
                                @if($nextMatch)
                                    <span class="badge bg-success">
                                        vs {{ $nextMatch->opponent_name ?? '-' }} - {{ \Carbon\Carbon::parse($nextMatch->match_date)->format('Y-m-d H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">Brak nadchodzących meczów</span>
                                @endif
                            </div>
                            <a href="{{ route('teams.details', ['id' => $team->id]) }}" class="btn btn-outline-primary btn-sm ms-2">Szczegóły</a>
                            @if($team->leader_id !== Auth::id())
                                <form action="{{ route('teams.leave', ['id' => $team->id]) }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Opuść drużynę</button>
                                </form>
                            @endif
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection