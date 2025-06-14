<div class="col-md-6 main-content mx-auto">
  <div class="card p-3 mb-3 shadow-sm">
    <h5>
      <a class="text-decoration-none" data-bs-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse">
        Filters <i class="bi bi-chevron-down"></i>
      </a>
    </h5>
    <div class="collapse" id="filterCollapse">
      <form action="#" method="GET" class="d-flex flex-wrap gap-2">
        <div class="mb-3">
          <label for="filter_date" class="form-label">
            <i class="bi bi-calendar"></i> Date
          </label>
          <input type="date" id="filter_date" name="filter_date" class="form-control form-control-sm">
          <button type="button" class="btn btn-outline-secondary btn-sm mt-1" onclick="clearFilter('filter_date')">Clear</button>
        </div>
        <div class="mb-3">
          <label for="filter_time" class="form-label">
            <i class="bi bi-clock"></i> Time
          </label>
          <input type="time" id="filter_time" name="filter_time" class="form-control form-control-sm">
          <button type="button" class="btn btn-outline-secondary btn-sm mt-1" onclick="clearFilter('filter_time')">Clear</button>
        </div>
        <div class="mb-3">
          <label for="filter_team" class="form-label">
            <i class="bi bi-people"></i> Team
          </label>
          <select id="filter_team" name="filter_team" class="form-select form-select-sm">
            <option value="">Select Team</option>
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
        <img src="{{ asset('IMG/lol-logo.png') }}" alt="Team Logo" class="me-2" style="width: 40px; height: 40px;">
        <h5 class="mb-0">Example Team</h5>
      </div>
      <p class="bg-light shadow-sm rounded p-3">Example announcement description...</p>
      <div>
        <label class="form-label"><i class="bi bi-calendar-check"></i> Available Times</label>
        <ul class="list-unstyled">
          <li><i class='bi bi-clock me-2'></i>2025-06-20 19:00 - 2025-06-20 21:00</li>
        </ul>
        <label class="form-label"><i class="bi bi-calendar-event"></i> Select Date and Time</label>
        <input type="datetime-local" class="form-control" required>
        <button class="btn btn-outline-primary me-2 mt-2">Take</button>
      </div>
    </div>
  </div>
</div>