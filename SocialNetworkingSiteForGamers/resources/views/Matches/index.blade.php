
@extends('main')

@section('title', 'My Matches')

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .calendar-placeholder {
        background: #23272f;
        border-radius: 12px;
        min-height: 320px;
        color: #b0b8c1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')


<div class="container py-4">
    <h2 class="mb-4 text-primary">My Matches</h2>

    <!-- Filtry -->
  

    <!-- Kalendarz (placeholder) -->
   <div id="calendar" class="mb-4"></div>
        
    </div>
 <form class="mb-4" method="GET">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label for="search" class="form-label">Szukaj tytułu</label>
            <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Tytuł meczu">
        </div>
        <div class="col-md-3">
            <label for="gameFilter" class="form-label">Game</label>
            <select id="gameFilter" class="form-select" name="game_id">
                <option value="">All Games</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="statusFilter" class="form-label">Status</label>
            <select id="statusFilter" class="form-select" name="status">
                <option value="">All</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="played" {{ request('status') == 'played' ? 'selected' : '' }}>Played</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>
    <!-- Lista meczów -->
   <div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">All My Matches</h5>
    </div>
    <div class="card-body">
        <ul class="list-group">
            @forelse($filteredMatches as $match)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                       <strong>
    {{ $match->title ?? 'Brak tytułu' }}
</strong>
                        <div class="text-muted small">
                            {{ $match->match_date }} • {{ $match->game->name ?? '-' }}
                            @if($match->status === 'played')
                                <span class="badge bg-success ms-2">Played</span>
                            @elseif($match->status === 'canceled')
                                <span class="badge bg-danger ms-2">Canceled</span>
                            @else
                                <span class="badge bg-warning ms-2">Upcoming</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('matches.show', $match->id) }}" class="btn btn-outline-info btn-sm">Details</a>
                        @if($match->status !== 'played' && $match->status !== 'canceled')
    <a href="{{ route('matches.cancel', $match->id) }}"
       class="btn btn-outline-danger btn-sm ms-2"
       onclick="return confirm('Are you sure you want to cancel this match?');">
       Cancel
    </a>
@endif
                    </div>
                </li>
            @empty
                <li class="list-group-item">No matches found.</li>
            @endforelse
        </ul>
    </div>
</div>

<script>
    window.calendarEvents = @json($calendarEvents);
</script>
<script src="{{ asset('js/calendar.js') }}"></script>
@endsection