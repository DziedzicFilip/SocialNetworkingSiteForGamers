@extends('main')

@section('title', 'Szczegóły meczu')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary">Szczegóły meczu</h2>
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Tytuł:</strong> {{ $match->title ?? 'Brak tytułu' }}</p>
            <p><strong>Opis:</strong> {{ $match->description ?? 'Brak opisu' }}</p>
            <p><strong>Gra:</strong> {{ $match->game->name ?? 'Brak danych' }}</p>
            <p><strong>Status:</strong> {{ $match->status }}</p>
            <p><strong>Data i godzina:</strong> {{ $match->match_date }}</p>
            <p><strong>Miejsce/Platforma:</strong> {{ $match->location ?? 'Online' }}</p>
        </div>
    </div>

    @if(Auth::id() === optional($match->team)->leader_id || Auth::id() === $match->creator_id)
    {{-- Edycja tylko dla właściciela meczu --}}
    <form method="POST" action="{{ route('matches.update', $match->id) }}">
        @csrf
        @method('PUT')
        <div class="card mb-3">
            <div class="card-header">
                <strong>Edycja meczu</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Tytuł meczu</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $match->title }}" maxlength="255">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Opis meczu</label>
                    <textarea name="description" id="description" class="form-control" rows="3" maxlength="2000">{{ $match->description }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="match_date" class="form-label">Data i godzina meczu</label>
                    <input type="datetime-local" name="match_date" id="match_date" class="form-control"
                        value="{{ \Carbon\Carbon::parse($match->match_date)->format('Y-m-d\TH:i') }}">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status meczu:</label>
                    <select name="status" id="status" class="form-select" style="max-width:200px;">
                        <option value="played" {{ $match->status == 'played' ? 'selected' : '' }}>Rozegrany</option>
                        <option value="canceled" {{ $match->status == 'canceled' ? 'selected' : '' }}>Odwołany</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="result" class="form-label">Rezultat:</label>
                    <select name="result" id="result" class="form-select" style="max-width:200px;">
                        <option value="win" {{ $match->matchParticipants->first()?->is_winner ? 'selected' : '' }}>Wygrany</option>
                        <option value="lose" {{ !$match->matchParticipants->first()?->is_winner ? 'selected' : '' }}>Przegrany</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-3">Zapisz zmiany</button>
    </form>
    @endif

    <div class="card mt-4">
        <div class="card-header">
            <strong>Uczestnicy</strong>
        </div>
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Nick</th>
                </tr>
            </thead>
            <tbody>
                @foreach($match->matchParticipants as $participant)
                    <tr>
                        <td>{{ $participant->user->username ?? 'Nieznany użytkownik' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('matches.index') }}" class="btn btn-secondary mt-3">Powrót do listy meczów</a>
</div>
@endsection