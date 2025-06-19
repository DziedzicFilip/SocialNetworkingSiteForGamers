
<div class="card p-3 mb-3 shadow-sm">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Filters</h5>
    <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
      Show/Hide Filters
    </button>
  </div>
  <div class="collapse" id="filtersCollapse">
   <form action="{{ route('home') }}" method="GET" class="d-flex flex-wrap gap-2 mt-3">
    <div class="mb-3">
        <label for="filter_title" class="form-label">Search title</label>
        <input type="text" id="filter_title" name="filter_title" value="{{ request('filter_title') }}" class="form-control form-control-sm" placeholder="Enter title...">
    </div>
    <div class="mb-3">
        <label for="filter_game" class="form-label">Game</label>
        <select id="filter_game" name="filter_game" class="form-select form-select-sm">
            <option value="">All games</option>
            @foreach($games as $game)
                <option value="{{ $game->id }}" @if(request('filter_game') == $game->id) selected @endif>{{ $game->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="filter_team" class="form-label">Team</label>
        <select id="filter_team" name="filter_team" class="form-select form-select-sm">
            <option value="">All teams</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" @if(request('filter_team') == $team->id) selected @endif>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
    <label for="filter_user" class="form-label">User nickname</label>
    <input type="text" id="filter_user" name="filter_user" value="{{ request('filter_user') }}" class="form-control form-control-sm" placeholder="Enter nickname...">
</div>
    <div class="mb-3">
        <label for="filter_type" class="form-label">Post type</label>
        <select id="filter_type" name="filter_type" class="form-select form-select-sm">
            <option value="">All types</option>
            <option value="discussion" @if(request('filter_type') == 'discussion') selected @endif>Discussion</option>
            <option value="casual" @if(request('filter_type') == 'casual') selected @endif>Casual</option>
            <option value="team" @if(request('filter_type') == 'team') selected @endif>Team recruitment</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="filter_date" class="form-label">Date</label>
        <input type="date" id="filter_date" name="filter_date" value="{{ request('filter_date') }}" class="form-control form-control-sm">
    </div>
    <div class="mb-3">
        <label for="filter_sort" class="form-label">Sort by</label>
        <select id="filter_sort" name="filter_sort" class="form-select form-select-sm">
            <option value="desc" @if(request('filter_sort') == 'desc') selected @endif>Newest first</option>
            <option value="asc" @if(request('filter_sort') == 'asc') selected @endif>Oldest first</option>
        </select>
    </div>
    <div class="w-100"></div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">Clear All</a>
    </div>
</form>
  </div>
</div>


<div class="posts">
  @foreach($posts as $post)
    <div class="card p-3 mb-3">
      <div class="d-flex align-items-center mb-3">
        @if($post->game && $post->game->image)
         <img src="{{ asset($post->game->image) }}" alt="{{ $team->game->name }}" alt="Game Logo" class="me-2" style="width: 150px; height: 150px;">
       
          @endif
        <div>
          <h5 class="mb-1">{{ $post->title }}</h5>
      <small class="text-muted">
    @if($post->play_time)
        {{ \Carbon\Carbon::parse($post->play_time)->format('Y-m-d H:i') }}
    @else
        {{ $post->created_at }}
    @endif
</small>
          <div class="mt-1">
            <span class="fw-bold">{{ $post->user->username }}</span>
            @if($post->team)
              <span class="text-muted">| Team: {{ $post->team->name }}</span>
            @endif
          </div>
        </div>
      </div>
      <div class="mb-2">
        <span class="badge bg-primary me-2">Type: {{ ucfirst($post->type) }}</span>
        @if($post->game)
          <span class="badge bg-success">Game: {{ $post->game->name }}</span>
        @endif
      </div>
      <p class="mb-2">{{ $post->content }}</p>

   @if($post->type === 'casual')
    <div>
  <strong>Play date:</strong> {{ $post->play_time ? \Carbon\Carbon::parse($post->play_time)->format('Y-m-d H:i') : '-' }}</div>
    @if($post->max_players)
        <div class="mb-2">
            <strong>Players:</strong> {{ $post->current_players }}/{{ $post->max_players }}
        </div>
    @endif
    @if($post->already_applied)
        <div class="alert alert-info mt-2 mb-0 p-1">Already Sign up</div>
    @else
        <form action="{{ route('posts.apply', $post->id) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-outline-primary mb-2">Sign UP</button>
        </form>
    @endif
@endif

@if($post->type === 'team')
    @php
    $user = Auth::user();
    $alreadyInTeamForThisGame = false;
    $isLeader = false;
    if($user && isset($userTeams)) {
        foreach($userTeams as $team) {
            if($team->game_id == $post->game_id) {
                if($team->leader_id == $user->id) {
                    $isLeader = true;
                }
                $alreadyInTeamForThisGame = true;
            }
        }
    }
@endphp

@if(!$user)
    <div class="text-muted">Zaloguj się, aby dołączyć do drużyny.</div>
@elseif($isLeader)
    <div class="text-muted">Jesteś liderem drużyny w tej grze.</div>
@elseif($alreadyInTeamForThisGame)
    <div class="text-muted">Należysz już do drużyny w tej grze.</div>
@elseif($post->already_applied)
    <div class="alert alert-info mt-2 mb-0 p-1">Już zgłosiłeś się do tej drużyny!</div>
@else
    <form action="{{ route('posts.apply', $post->id) }}" method="POST" class="mt-2">
        @csrf
        <button type="submit" class="btn btn-outline-primary mb-2">Dołącz do drużyny</button>
    </form>
@endif
@endif

<!-- 
      <button class="btn btn-link p-0 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#commentsCollapse{{ $post->id }}" aria-expanded="false" aria-controls="commentsCollapse{{ $post->id }}">
        Show/Hide Comments
      </button>
      <div class="collapse" id="commentsCollapse{{ $post->id }}">
        <div class="card card-body bg-light">
          @foreach($post->comments ?? [] as $comment)
            <div class="mb-2">
              <strong>{{ $comment->user->username }}</strong>: {{ $comment->content }}
            </div>
          @endforeach
          <form>
            <div class="mb-2">
              <input type="text" class="form-control form-control-sm" placeholder="Add a comment...">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Comment</button>
          </form>
        </div>
      </div>
       -->
    </div>
    
  @endforeach
 
</div>