


  <div class="col-md-3">
    <div class="card text-center p-3 mb-3">
      <img src="../IMG/lol-logo.png" class="profile-img mx-auto" alt="logo druzyny" />
      <h5 class="mt-3">Nickname</h5>
      <p class="text-muted">Team Name</p>
    </div>
    <div class="card text-center p-4 mb-3">
      <h5>Add Announcement</h5>
      <form action="#" method="POST">
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea id="description" name="description" class="form-control" required></textarea>
        </div>
        <div id="availability-container">
          <div class="mb-3 p-3 bg-light shadow-sm rounded">
            <label for="availability_start" class="form-label">
                <i class="bi bi-calendar-check"></i> Availability Start
            </label>
            <input type="datetime-local" name="availability_start[]" class="form-control" required>
            <label for="availability_end" class="form-label mt-2">
                <i class="bi bi-calendar-check"></i> Availability End
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
  </div>

  