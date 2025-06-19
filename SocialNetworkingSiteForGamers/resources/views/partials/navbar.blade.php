<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">ScrimPlanner</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">Ogłoszenia</a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="{{ route('teams.my') }}">Drużyny</a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="{{ route('matches.index') }}">Mecze</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('posts.my') }}">Moje posty</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('profile.me') }}">Profil</a>
      </li>
      @if(Auth::check() && Auth::user()->role === 'admin')
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.index') }}">Panel administratora</a>
      </li>
      @endif
      <li class="nav-item">
        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
          @csrf
        </form>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          Wyloguj się
        </a>
      </li>
    </ul>
  </div>
</nav>