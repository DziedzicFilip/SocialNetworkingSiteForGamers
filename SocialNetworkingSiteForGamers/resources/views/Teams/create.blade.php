@extends('main')

@section('title', 'Stwórz drużynę')

@section('content')
<div class="container py-4">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
    <h2 class="mb-4 text-primary">Stwórz nową drużynę</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('teams.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nazwa drużyny</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="game_id" class="form-label">Gra</label>
            <select name="game_id" id="game_id" class="form-select" required>
                <option value="">Wybierz grę</option>
                @foreach($availableGames as $game)
                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Stwórz drużynę</button>
    </form>
</div>
@endsection