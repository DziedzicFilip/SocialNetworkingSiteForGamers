@extends('main')

@section('title', 'My Teams')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
{{-- Debug: Wyświetl kolekcję drużyn (usuń w produkcji) --}}
{{-- <pre>{{ print_r($teams, true) }}</pre> --}}

<div class="container py-4">
    <h2 class="mb-4 text-primary">My Teams</h2>

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
                                        <strong>Members:</strong>
                                        @foreach($allMembers as $member)
                                            <span class="badge bg-secondary">{{ $member->username }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    @if($team->leader_id === Auth::id())
                                        <span class="badge bg-primary">Leader</span>
                                    @else
                                        <span class="badge bg-info">Member</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Next Match:</strong>
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
                                    <span class="text-muted">No upcoming matches</span>
                                @endif
                            </div>
                            <a href="{{ route('teams.details', ['id' => $team->id]) }}" class="btn btn-outline-primary btn-sm ms-2">Details</a>
                            @if($team->leader_id !== Auth::id())
                                <form action="{{ route('teams.leave', ['id' => $team->id]) }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Leave team</button>
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