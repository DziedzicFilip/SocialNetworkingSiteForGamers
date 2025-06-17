
  
    <div class="card p-3 mb-3 shadow-sm">
  <h5><i class="bi bi-calendar"></i> Upcoming Events</h5>
  <ul class="list-unstyled">
    @forelse($upcomingEvents as $event)
      <li class="border-bottom py-2">
        <strong>{{ \Carbon\Carbon::parse($event->play_time)->format('Y-m-d H:i') }}</strong><br>
        Title: {{ $event->title }}<br>
        Game: {{ $event->game->name ?? '-' }}<br>
        Players: {{ $event->current_players }}/{{ $event->max_players }}<br>
        @if($event->user_id == Auth::id())
          <span class="badge bg-info">Your post</span>
        @else
          <span class="badge bg-success">You participate</span>
        @endif
      </li>
    @empty
      <li class="py-2 text-muted">No upcoming events.</li>
    @endforelse
  </ul>
</div>
    <div class="card p-3 mb-3 shadow-sm">
  <h5 class="text-primary"><i class="bi bi-clock"></i> Pending Requests</h5>
  <ul class="list-unstyled">
    @forelse($pendingRequests as $request)
      <li class="border-bottom py-2">
        <strong>{{ $request->post->title }}</strong><br>
        Player: {{ $request->user->username }}<br>
        @foreach($pendingRequests as $pending)
    <div class="mb-2">
        <!-- Link otwierający modal -->
        <a href="#" data-bs-toggle="modal" data-bs-target="#userProfileModal{{ $pending->user->id }}">
            {{ $pending->user->username }}
        </a>
        <!-- Lub link do pełnego profilu -->
       
    </div>

    <!-- Modal z profilem gracza -->
    <div class="modal fade" id="userProfileModal{{ $pending->user->id }}" tabindex="-1" aria-labelledby="userProfileModalLabel{{ $pending->user->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userProfileModalLabel{{ $pending->user->id }}">Profil: {{ $pending->user->username }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img src="{{ asset($pending->user->profile_image ?? 'IMG/default-avatar.jpg') }}" alt="avatar" class="rounded-circle mb-2" style="width:80px;height:80px;">
            
            <div><strong>Bio:</strong> {{ $pending->user->bio ?? '-' }}</div>
          </div>
          <div class="modal-footer">
            <a href="{{ route('profile.show', $pending->user->id) }}" class="btn btn-primary">Pełny profil</a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
          </div>
        </div>
      </div>
    </div>
@endforeach
        <form action="{{ route('posts.acceptRequest', $request->id) }}" method="POST" class="d-inline">
          @csrf
          <button class="btn btn-primary btn-sm">Accept</button>
        </form>
        <form action="{{ route('posts.rejectRequest', $request->id) }}" method="POST" class="d-inline">
          @csrf
          <button class="btn btn-warning btn-sm">Reject</button>
        </form>
      </li>
    @empty
      <li class="py-2 text-muted">No pending requests.</li>
    @endforelse
  </ul>
</div>
 
