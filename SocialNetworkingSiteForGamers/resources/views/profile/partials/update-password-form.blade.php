<section class="mt-5">
    <h3 class="mb-3 text-primary">Zmień hasło</h3>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="current_password" class="form-label">Obecne hasło</label>
            <input id="current_password" name="current_password" type="password" class="form-control" required>
            @error('current_password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nowe hasło</label>
            <input id="password" name="password" type="password" class="form-control" required>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Powtórz nowe hasło</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
            @error('password_confirmation')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Zapisz</button>
        @if (session('status') === 'password-updated')
            <span class="text-success ms-3">Zapisano.</span>
        @endif
    </form>
</section>