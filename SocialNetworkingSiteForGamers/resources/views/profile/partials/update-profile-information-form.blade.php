
<section>
    <h3 class="mb-3 text-primary">Profile Information</h3>
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input id="username" name="username" type="text" class="form-control" value="{{ old('username', $user->username) }}" required autofocus autocomplete="username">
            @error('username')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="email">
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success ms-3">Saved.</span>
        @endif
    </form>
</section>