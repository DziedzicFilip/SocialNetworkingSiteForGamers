
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">ScrimPlanner</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">Announcements</a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="{{ route('teams.my') }}">Teams</a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="{{ route('matches.index') }}">Matches</a>
      </li>
      <li class="nav-item">
    <a class="nav-link" href="{{ route('posts.my') }}">My Posts</a>
</li>
      
      <li class="nav-item">
    <a class="nav-link" href="{{ route('profile.me') }}">Profile</a>
</li>
@if(Auth::check() && Auth::user()->role === 'admin')
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.index') }}">Admin Panel</a>
</li>
@endif
      <li class="nav-item">
        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
          @csrf
        </form>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          Logout
        </a>
      </li>
    </ul>
  </div>
</nav>