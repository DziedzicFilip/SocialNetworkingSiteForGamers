<div class="card text-center p-3 mb-3">
  <img src="{{ asset(Auth::user()->profile_image ?? 'IMG/LogoArcadeUnionDefault.png') }}" class="profile-img mx-auto" alt="logo drużyny" /> 
  <h5 class="mt-3">{{ Auth::user()->username }}</h5>
</div>
<div class="card text-center p-4 mb-3">
  <h5>Dodaj ogłoszenie</h5>
  <form action="{{ route('posts.store') }}" method="POST" class="text-start mt-3">
    @csrf
    <label for="type" class="form-label">Typ:</label>
    <select name="type" id="type" class="form-select mb-2" required onchange="togglePlayersField()">
        <option value="discussion">Dyskusja</option>
        <option value="casual">Szukam graczy</option>
        <option value="team">Rekrutacja do drużyny</option>
    </select>
    <!-- Wybór gry -->
    <div id="gameField">
        <label for="game_id" class="form-label">Gra:</label>
        <select name="game_id" id="game_id" class="form-select mb-2">
            <option value="">Wybierz grę...</option>
            @foreach($games as $game)
                <option value="{{ $game->id }}">{{ $game->name }}</option>
            @endforeach
        </select>
    </div>
    <label for="title" class="form-label">Tytuł:</label>
    <input type="text" name="title" id="title" class="form-control mb-2" maxlength="255" required>
    <!-- Opis -->
    <label for="content" class="form-label">Opis:</label>
    <textarea name="content" id="content" class="form-control mb-2" rows="3" required></textarea>

    <!-- Wybór drużyny (jeśli jesteś liderem) -->
    @if($leaderTeams->count() > 0)
    <label for="team_id" class="form-label">Twoja drużyna:</label>
    <select name="team_id" id="team_id" class="form-select mb-2">
        <option value="">Brak</option>
        @foreach($leaderTeams as $team)
            <option value="{{ $team->id }}">{{ $team->name }}</option>
        @endforeach
    </select>
    @endif

    <!-- Liczba szukanych graczy -->
    <div id="playersField">
        <div id="playTimeField">
            <label for="play_time" class="form-label">Data grania:</label>
            <input type="datetime-local" name="play_time" id="play_time" class="form-control mb-2">
        </div>
        <label for="max_players" class="form-label">Liczba poszukiwanych graczy:</label>
        <input type="number" name="max_players" id="max_players" class="form-control mb-2" min="1">
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-2">Dodaj ogłoszenie</button>
    @if ($errors->any())
    <div class="alert alert-danger mt-2">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
  </form>
  <script>
  function togglePlayersField() {
      const type = document.getElementById('type').value;
      const playersField = document.getElementById('playersField');
      const gameField = document.getElementById('gameField');
      const playTimeField = document.getElementById('playTimeField');

      if(type === 'discussion') {
          playersField.style.display = 'none';
          gameField.style.display = 'block';
      } else if(type === 'team') {
          playersField.style.display = 'block';
          gameField.style.display = 'none';
          playTimeField.style.display = 'none';
      } else {
          playersField.style.display = 'block';
          gameField.style.display = 'block';
          playTimeField.style.display = 'block';
      }
  }
  document.addEventListener('DOMContentLoaded', togglePlayersField);
  </script>
</div>