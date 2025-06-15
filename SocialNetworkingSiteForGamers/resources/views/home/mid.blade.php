
<div class="card p-3 mb-3 shadow-sm">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Filters</h5>
    <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
      Show/Hide Filters
    </button>
  </div>
  <div class="collapse" id="filtersCollapse">
    <form action="#" method="GET" class="d-flex flex-wrap gap-2 mt-3">
      <div class="mb-3">
        <label for="filter_date" class="form-label">Date</label>
        <input type="date" id="filter_date" name="filter_date" class="form-control form-control-sm">
        <button type="button" class="btn btn-outline-secondary btn-sm mt-1" onclick="clearFilter('filter_date')">Clear</button>
      </div>
      <div class="mb-3">
        <label for="filter_time" class="form-label">Time</label>
        <input type="time" id="filter_time" name="filter_time" class="form-control form-control-sm">
        <button type="button" class="btn btn-outline-secondary btn-sm mt-1" onclick="clearFilter('filter_time')">Clear</button>
      </div>
      <div class="mb-3">
        <label for="filter_team" class="form-label">Team</label>
        <select id="filter_team" name="filter_team" class="form-select form-select-sm">
          <option value="">Select team</option>
        </select>
        <button type="button" class="btn btn-outline-secondary btn-sm mt-1" onclick="clearFilter('filter_team')">Clear</button>
      </div>
      <div class="w-100"></div>
      <div class="mb-3">
        <button type="button" class="btn btn-secondary btn-sm" onclick="clearAllFilters()">Clear All</button>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      </div>
    </form>
  </div>
</div>

<div class="posts">
  <div class="card p-3 mb-3">
    <div class="d-flex align-items-center mb-3">
      <img src="{{ asset('IMG/lol-logo.png') }}" alt="Game Logo" class="me-2" style="width: 40px; height: 40px;">
      <div>
        <h5 class="mb-1">Looking for Scrim</h5>
        <small class="text-muted">2025-06-20 19:00 - 2025-06-20 21:00</small>
        <div class="mt-1">
          <span class="fw-bold">User123</span>
          <span class="text-muted">| Team: ProGamers</span>
        </div>
      </div>
    </div>
    <div class="mb-2">
      <span class="badge bg-primary me-2">Type: Tournament</span>
      <span class="badge bg-success">Game: League of Legends</span>
    </div>
    <p class="mb-2">We are looking for a serious opponent for a tournament practice. All ranks welcome!</p>
    <button class="btn btn-outline-primary mb-2" type="button">Sign Up</button>
    <button class="btn btn-link p-0 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#commentsCollapse1" aria-expanded="false" aria-controls="commentsCollapse1">
      Show/Hide Comments
    </button>
    <div class="collapse" id="commentsCollapse1">
      <div class="card card-body bg-light">
        <div class="mb-2">
          <strong>User123:</strong> Good luck!
        </div>
        <div class="mb-2">
          <strong>ProTeam:</strong> We are interested, DM us!
        </div>
        <form>
          <div class="mb-2">
            <input type="text" class="form-control form-control-sm" placeholder="Add a comment...">
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Comment</button>
        </form>
      </div>
    </div>
  </div>
</div>