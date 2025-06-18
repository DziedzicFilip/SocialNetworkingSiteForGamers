

  
    <div class="card text-center p-3 mb-3">
  <img src="{{ asset(Auth::user()->profile_image ?? 'IMG/LogoArcadeUnionDefault.png') }}" class="profile-img mx-auto" alt="team logo" /> 
 <h5 class="mt-3">{{ Auth::user()->username }}</h5>
  
</div>
<div class="card text-center p-4 mb-3">
  <h5>Add Announcement</h5>
  <form action="{{ route('posts.store') }}" method="POST" class="text-start mt-3">
    @csrf
    <label for="type" class="form-label">Type:</label>
    <select name="type" id="type" class="form-select mb-2" required onchange="togglePlayersField()">
        <option value="discussion">Discussion</option>
        <option value="casual">Looking for Gamers </option>
        <option value="team">Team recruitment</option>
    </select>
    <!-- Wybór gry -->
   <label for="game_id" class="form-label">Game:</label>
<select name="game_id" id="game_id" class="form-select mb-2" >
    <option value="">Select game...</option>
    @foreach($games as $game)
        <option value="{{ $game->id }}">{{ $game->name }}</option>
    @endforeach
</select>
    <label for="title" class="form-label">Title:</label>
<input type="text" name="title" id="title" class="form-control mb-2" maxlength="255" required>
    <!-- Opis -->
    <label for="content" class="form-label">Description:</label>
    <textarea name="content" id="content" class="form-control mb-2" rows="3" required></textarea>

    <!-- Data grania -->
  
    <!-- Typ posta -->
     

    <!-- Wybór drużyny (jeśli jesteś liderem) -->
    @if($leaderTeams->count() > 0)
    <label for="team_id" class="form-label">Your team (if you are a leader):</label>
    <select name="team_id" id="team_id" class="form-select mb-2">
        <option value="">None</option>
        @foreach($leaderTeams as $team)
            <option value="{{ $team->id }}">{{ $team->name }}</option>
        @endforeach
    </select>
@endif

    <!-- Liczba szukanych graczy -->
    <div id="playersField">
       <label for="play_time" class="form-label">Date of playing:</label>
<input type="datetime-local" name="play_time" id="play_time" class="form-control mb-2">

        <label for="max_players" class="form-label">Number of players wanted:</label>
        <input type="number" name="max_players" id="max_players" class="form-control mb-2" min="1">
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-2">Add Announcement</button>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
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
    if(type === 'discussion') {
        playersField.style.display = 'none';
    } else {
        playersField.style.display = 'block';
    }
}
// Wywołaj na starcie, jeśli edytujesz formularz lub masz domyślną wartość
document.addEventListener('DOMContentLoaded', togglePlayersField);
</script>
</div>
  