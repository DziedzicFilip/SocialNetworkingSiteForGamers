
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">ScrimPlanner</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#">Announcements</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Team</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Matches</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('profile.edit') }}">Edit Profile</a>
      </li>
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