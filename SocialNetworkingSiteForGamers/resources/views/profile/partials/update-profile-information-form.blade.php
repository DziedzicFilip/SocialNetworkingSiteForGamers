<section>
    <h3 class="mb-3 text-primary">Informacje o profilu</h3>
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="username" class="form-label">Nazwa użytkownika</label>
            <input id="username" name="username" type="text" class="form-control" value="{{ old('username', $user->username) }}" required autofocus autocomplete="username">
            @error('username')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="email">
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 text-center">
            <label for="avatar_url" class="form-label d-block">Zdjęcie profilowe</label>
            <img src="{{ asset(Auth::user()->profile_image ?? 'IMG/default-avatar.jpg') }}" alt="Avatar" class="rounded-circle mb-2" style="width: 90px; height: 90px; object-fit: cover; border: 2px solid #4f8cff;">
           <input class="form-control mt-2" type="file" id="profile_image" name="profile_image" accept="image/*">
@error('profile_image')
    <div class="text-danger small mt-1">{{ $message }}</div>
@enderror
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">Opis</label>
            <textarea id="bio" name="bio" class="form-control" rows="3" maxlength="255">{{ old('bio', Auth::user()->bio) }}</textarea>
            @error('bio')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Zapisz</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success ms-3">Zapisano.</span>
        @endif
    </form>
</section>