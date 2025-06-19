{{-- filepath: resources/views/admin/index.blade.php --}}
@extends('main')

@section('title', 'Admin Panel')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary">Admin Panel</h2>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    {{-- Dodawanie gry --}}
    <div class="card mb-4">
        <div class="card-header">Dodaj nową grę</div>
        <div class="card-body">
           <form method="POST" action="{{ route('admin.games.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="game_name" class="form-label">Nazwa gry</label>
        <input type="text" name="name" id="game_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="game_image" class="form-label">Obrazek gry</label>
        <input type="file" name="image" id="game_image" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-success">Dodaj grę</button>
</form>
        </div>
    </div>

    {{-- Tworzenie użytkownika --}}
    <div class="card mb-4">
        <div class="card-header">Dodaj użytkownika</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Nazwa użytkownika</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Rola</label>
                    <select name="role" id="role" class="form-select">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Dodaj użytkownika</button>
            </form>
        </div>
    </div>

    {{-- Edycja gry --}}
    <div class="card mb-4">
        <div class="card-header">Edytuj grę</div>
        <div class="card-body">
           <form method="POST" action="{{ route('admin.games.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="edit_game_id" class="form-label">Wybierz grę</label>
        <select name="game_id" id="edit_game_id" class="form-select" required>
            @foreach($games as $game)
                <option value="{{ $game->id }}">{{ $game->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="edit_game_name" class="form-label">Nowa nazwa gry</label>
        <input type="text" name="name" id="edit_game_name" class="form-control" >
    </div>
    <div class="mb-3">
        <label for="edit_game_image" class="form-label">Nowy obrazek gry</label>
        <input type="file" name="image" id="edit_game_image" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
</form>
        </div>
    </div>
    <form method="GET" class="mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Szukaj gry po nazwie">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Szukaj</button>
        </div>
    </div>
</form>
    <div class="card mb-4">
        
    <div class="card-header">
        <strong>Lista gier</strong>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa gry</th>
                    <th>Obrazek</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($games as $game)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $game->name }}</td>
                    <td>
                        @if($game->image)
                            <img src="{{ asset($game->image) }}" alt="{{ $game->name }}" style="max-width: 60px; max-height: 40px;">
                        @else
                            <span class="text-muted">Brak</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.games.delete') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="game_id" value="{{ $game->id }}">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Na pewno usunąć tę grę?')">
                                Usuń
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($games->isEmpty())
                <tr>
                    <td colspan="4" class="text-center text-muted">Brak gier w bazie.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection