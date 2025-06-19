@extends('main')

@section('title', 'Moje posty')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Moje posty</h2>

    {{-- Filtry --}}
    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Szukaj po tytule">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">Wszystkie typy</option>
                    <option value="discussion" {{ request('type')=='discussion'?'selected':'' }}>Dyskusja</option>
                    <option value="casual" {{ request('type')=='casual'?'selected':'' }}>Luźna gra</option>
                    <option value="team" {{ request('type')=='team'?'selected':'' }}>Rekrutacja do drużyny</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="game_id" class="form-select">
                    <option value="">Wszystkie gry</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ request('game_id')==$game->id?'selected':'' }}>{{ $game->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="visible" class="form-select">
                    <option value="">Aktywność</option>
                    <option value="1" {{ request('visible') === '1' ? 'selected' : '' }}>Aktywny</option>
                    <option value="0" {{ request('visible') === '0' ? 'selected' : '' }}>Nieaktywny</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100">Filtruj</button>
            </div>
        </div>
    </form>

    {{-- Lista postów --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tytuł</th>
                <th>Typ</th>
                <th>Gra</th>
                <th>Aktywność</th>
                <th>Data dodania</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>
                    @if($post->type === 'discussion')
                        Dyskusja
                    @elseif($post->type === 'casual')
                        Luźna gra
                    @elseif($post->type === 'team')
                        Rekrutacja do drużyny
                    @else
                        {{ $post->type }}
                    @endif
                </td>
                <td>{{ $post->game->name ?? '-' }}</td>
                <td>
                    @if($post->visible)
                        <span class="badge bg-success">Tak</span>
                    @else
                        <span class="badge bg-secondary">Nie</span>
                    @endif
                </td>
                <td>{{ $post->created_at }}</td>
                <td>
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning">Edytuj</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Brak postów.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
    {{ $posts->links() }}
</div>
</div>
@endsection