@extends('main')

@section('title', 'Edytuj post')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edytuj post</h2>
    <form method="POST" action="{{ route('posts.update', $post->id) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tytuł</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Treść</label>
            <textarea name="content" class="form-control" rows="10" required>{{ old('content', $post->content) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Typ</label>
            <input type="text" class="form-control" value="{{ ucfirst($post->type) }}" readonly>
            <input type="hidden" name="type" value="{{ $post->type }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Gra</label>
            <select name="game_id" class="form-select">
                <option value="">Brak</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ $post->game_id==$game->id?'selected':'' }}>{{ $game->name }}</option>
                @endforeach
            </select>
        </div>
        @if($post->type !== 'discussion')
            <div class="mb-3">
                <label class="form-label">Max graczy</label>
                <input type="number" name="max_players" class="form-control" value="{{ old('max_players', $post->max_players) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Data gry</label>
                <input type="datetime-local" name="play_time" class="form-control"
                    value="{{ old('play_time', $post->play_time ? \Carbon\Carbon::parse($post->play_time)->format('Y-m-d\TH:i') : '') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Drużyna</label>
                <select name="team_id" class="form-select">
                    <option value="">Brak</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ $post->team_id==$team->id?'selected':'' }}>{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="mb-3">
    <label class="form-label">Aktywny</label>
    @if($post->type === 'discussion')
        <select name="visible" class="form-select">
            <option value="1" {{ $post->visible ? 'selected' : '' }}>Tak</option>
            <option value="0" {{ !$post->visible ? 'selected' : '' }}>Nie</option>
        </select>
    @else
        <input type="text" class="form-control" value="Nie" readonly>
        <input type="hidden" name="visible" value="0">
    @endif
</div>
        <button class="btn btn-success">Zapisz zmiany</button>
        <a href="{{ route('posts.my') }}" class="btn btn-secondary">Powrót</a>
    </form>
</div>
@endsection