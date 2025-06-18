@extends('main')

@section('title', 'Szczegóły meczu')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary">Szczegóły meczu</h2>
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Gra:</strong> {{ $match->game->name ?? 'Brak danych' }}</p>
            <p><strong>Status:</strong> {{ $match->status }}</p>
            <p><strong>Data i godzina:</strong> {{ $match->match_date }}</p>
            <p><strong>Miejsce/Platforma:</strong> {{ $match->location ?? 'Online' }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('matches.update', $match->id) }}">
        @csrf
        @method('PUT')
        <div class="card">
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
        <div class="mt-3">
            <label for="status" class="form-label">Status meczu:</label>
            <select name="status" id="status" class="form-select" style="max-width:200px;">
                <option value="played" {{ $match->status == 'played' ? 'selected' : '' }}>Rozegrany</option>
                <option value="canceled" {{ $match->status == 'canceled' ? 'selected' : '' }}>Odwołany</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="result" class="form-label">Rezultat:</label>
            <select name="result" id="result" class="form-select" style="max-width:200px;">
                <option value="win" {{ $match->participants->first()->is_winner ? 'selected' : '' }}>Wygrany</option>
                <option value="lose" {{ !$match->participants->first()->is_winner ? 'selected' : '' }}>Przegrany</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success mt-3">Zapisz wynik</button>
    </form>

    <a href="{{ route('matches.index') }}" class="btn btn-secondary mt-3">Powrót do listy meczów</a>
</div>
@endsection