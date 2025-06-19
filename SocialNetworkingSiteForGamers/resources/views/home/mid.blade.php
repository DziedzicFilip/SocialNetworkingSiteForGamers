{{-- filepath: resources/views/home/mid.blade.php --}}
<div class="card p-3 mb-3 shadow-sm">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Filtry</h5>
    <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
      Pokaż/Ukryj filtry
    </button>
  </div>
  <div class="collapse" id="filtersCollapse">
   <form action="{{ route('home') }}" method="GET" class="d-flex flex-wrap gap-2 mt-3">
    <div class="mb-3">
        <label for="filter_title" class="form-label">Szukaj po tytule</label>
        <input type="text" id="filter_title" name="filter_title" value="{{ request('filter_title') }}" class="form-control form-control-sm" placeholder="Wpisz tytuł...">
    </div>
    <div class="mb-3">
        <label for="filter_game" class="form-label">Gra</label>
        <select id="filter_game" name="filter_game" class="form-select form-select-sm">
            <option value="">Wszystkie gry</option>
            @foreach($games as $game)
                <option value="{{ $game->id }}" @if(request('filter_game') == $game->id) selected @endif>{{ $game->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="filter_team" class="form-label">Drużyna</label>
        <select id="filter_team" name="filter_team" class="form-select form-select-sm">
            <option value="">Wszystkie drużyny</option>
            @foreach($teams as $team)
                <option value="{{ $team->id }}" @if(request('filter_team') == $team->id) selected @endif>{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="filter_user" class="form-label">Nick użytkownika</label>
        <input type="text" id="filter_user" name="filter_user" value="{{ request('filter_user') }}" class="form-control form-control-sm" placeholder="Wpisz nick...">
    </div>
    <div class="mb-3">
        <label for="filter_type" class="form-label">Typ posta</label>
        <select id="filter_type" name="filter_type" class="form-select form-select-sm">
            <option value="">Wszystkie typy</option>
            <option value="discussion" @if(request('filter_type') == 'discussion') selected @endif>Dyskusja</option>
            <option value="casual" @if(request('filter_type') == 'casual') selected @endif>Luźna gra</option>
            <option value="team" @if(request('filter_type') == 'team') selected @endif>Rekrutacja do drużyny</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="filter_date" class="form-label">Data</label>
        <input type="date" id="filter_date" name="filter_date" value="{{ request('filter_date') }}" class="form-control form-control-sm">
    </div>
    <div class="mb-3">
        <label for="filter_sort" class="form-label">Sortuj według</label>
        <select id="filter_sort" name="filter_sort" class="form-select form-select-sm">
            <option value="desc" @if(request('filter_sort') == 'desc') selected @endif>Najnowsze najpierw</option>
            <option value="asc" @if(request('filter_sort') == 'asc') selected @endif>Najstarsze najpierw</option>
        </select>
    </div>
    <div class="w-100"></div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary btn-sm">Filtruj</button>
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">Wyczyść wszystko</a>
    </div>
</form>
  </div>
</div>

<div class="posts">
@foreach($posts as $post)
<div class="card p-3 mb-3 shadow-sm">
  <div class="d-flex align-items-start">
    {{-- Awatar użytkownika --}}
    <img src="{{ asset($post->user->profile_image ?? 'IMG/LogoArcadeUnionDefault.png') }}"
         alt="avatar"
         class="rounded-circle me-3"
         style="width: 56px; height: 56px; object-fit: cover; border: 2px solid #4f8cff;">
    <div class="flex-grow-1">
      <div class="d-flex align-items-center mb-1">
        <span class="fw-bold me-2">{{ $post->user->username }}</span>
        <small class="text-muted">
          @if($post->play_time)
            {{ \Carbon\Carbon::parse($post->play_time)->format('Y-m-d H:i') }}
          @else
            {{ $post->created_at }}
          @endif
        </small>
        @if($post->team)
          <span class="badge bg-secondary ms-2">Drużyna: {{ $post->team->name }}</span>
        @endif
      </div>
      <h5 class="mb-1">{{ $post->title }}</h5>
      <p class="mb-2" style="white-space: pre-line;">{{ $post->content }}</p>
      <div class="mb-2">
        <span class="badge bg-primary me-2">
          Typ: {{ $post->type === 'discussion' ? 'Dyskusja' : ($post->type === 'casual' ? 'Luźna gra' : 'Rekrutacja do drużyny') }}
        </span>
        @if($post->game)
          <span class="badge bg-success">Gra: {{ $post->game->name }}</span>
        @endif
      </div>
      @if($post->type === 'casual')
        <div>
          <strong>Data gry:</strong> {{ $post->play_time ? \Carbon\Carbon::parse($post->play_time)->format('Y-m-d H:i') : '-' }}
        </div>
        @if($post->max_players)
          <div class="mb-2">
            <strong>Gracze:</strong> {{ $post->current_players }}/{{ $post->max_players }}
          </div>
        @endif
        @if($post->already_applied)
          <div class="alert alert-info mt-2 mb-0 p-1">Już zapisałeś się</div>
        @else
          <form action="{{ route('posts.apply', $post->id) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-outline-primary mb-2">Zapisz się</button>
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
    </div>
  </div>
</div>
@endforeach
</div>