

  
    <div class="card text-center p-3 mb-3">
  <img src="../IMG/lol-logo.png" class="profile-img mx-auto" alt="team logo" />
  <h5 class="mt-3">Nickname</h5>
  <p class="text-muted">Team Name</p>
</div>
<div class="card text-center p-4 mb-3">
  <h5>Add Announcement</h5>
  <form class="form-container" action="#" method="POST">
    <!-- Game type 
    <label for="game_type">Game type:</label>
    <select name="game_type" id="game_type" required>
      <option value="">Select...</option>
      <option value="fps">FPS</option>
      <option value="moba">MOBA</option>
      <option value="rpg">RPG</option>
      <option value="sport">Sports</option>
    </select>-->

    <!-- Specific game -->
    <label for="game">Game:</label>
    <select name="game" id="game" required>
      <option value="">Select game...</option>
      <option value="lol">League of Legends</option>
      <option value="csgo">CS:GO</option>
      <option value="valorant">Valorant</option>
      <option value="fifa">FIFA</option>
      <option value="dota2">Dota 2</option>
      <!-- Add more games as needed -->
    </select>

    <!-- Match type -->
    <label for="match_type">Match type:</label>
    <select name="match_type" id="match_type" required>
      <option value="">Select...</option>
      <option value="single">Single match</option>
      <option value="tournament">Tournament</option>
      <option value="league">League</option>
    </select>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea id="description" name="description" class="form-control" required></textarea>
    </div>
    <div id="availability-container">
      <div class="mb-3 p-3 shadow-sm rounded">
        <label for="availability_start" class="form-label">
          Availability Start
        </label>
        <input type="datetime-local" name="availability_start[]" class="form-control" required>
        <label for="availability_end" class="form-label mt-2">
          Availability End
        </label>
        <input type="datetime-local" name="availability_end[]" class="form-control" required>
      </div>
    </div>
    <div class="d-flex justify-content-center">
      <button type="button" class="btn btn-secondary mb-3" onclick="addAvailability()">+</button>
    </div>
    <button type="submit" class="btn btn-primary">Add</button>
  </form>
</div>
  