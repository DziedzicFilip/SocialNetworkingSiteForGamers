
<section class="mt-5">
    <h3 class="mb-3 text-danger">Delete Account</h3>
    <p class="mb-3">
        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
    </p>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        Delete Account
    </button>

    <!-- Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
            @csrf
            @method('delete')
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Are you sure you want to delete your account?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>
                <div class="mb-3">
                    <label for="delete_password" class="form-label">Password</label>
                    <input id="delete_password" name="password" type="password" class="form-control" placeholder="Password">
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </div>
        </form>
      </div>
    </div>
</section>