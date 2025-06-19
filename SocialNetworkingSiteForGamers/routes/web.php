<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//Route::get('/profile/me', function () { return view('profile.myProfile');})->name('profile.me');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
     Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
  
Route::post('/teams/{team}/add-match', [TeamController::class, 'addMatch'])->name('teams.addMatch');
});


Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');


Route::get('/matches/{id}/cancel', [MatchesController::class, 'cancel'])->name('matches.cancel');

Route::get('/profile/me', [ProfileController::class, 'myProfile'])
    ->middleware(['auth'])
    ->name('profile.me');


Route::get('/matches', [MatchesController::class, 'index'])
    ->middleware(['auth'])
    ->name('matches.index');
Route::post('/posts/{id}/apply', [PostController::class, 'apply'])->name('posts.apply');
Route::post('/requests/{id}/accept', [PostController::class, 'acceptRequest'])->name('posts.acceptRequest');
Route::post('/requests/{id}/reject', [PostController::class, 'rejectRequest'])->name('posts.rejectRequest');

Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::post('/posts/{id}/apply', [PostController::class, 'apply'])->name('posts.apply');
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

Route::get('/teams/my', [TeamController::class, 'index'])->middleware(['auth'])->name('teams.my');
Route::post('/teams/{id}/leave', [TeamController::class, 'leave'])->name('teams.leave');
Route::get('/teams/{id}', [TeamController::class, 'details'])->name('teams.details');

Route::get('/teams/{id}/details', [TeamController::class, 'details'])->middleware(['auth'])->name('teams.details');
Route::post('/teams/{id}/leave', [TeamController::class, 'leave'])->middleware(['auth'])->name('teams.leave');
Route::delete('/teams/{id}/delete', [TeamController::class, 'delete'])->middleware(['auth'])->name('teams.delete');
Route::post('/posts/{id}/rejectRequest', [PostController::class, 'rejectRequest'])->name('posts.rejectRequest');

Route::get('/matches/{id}', [MatchesController::class, 'show'])
    ->name('matches.details');
    Route::post('/matches/{id}/cancel', [MatchesController::class, 'cancel'])
    ->name('matches.cancel');
    Route::get('/matches/{id}', [MatchesController::class, 'show'])->name('matches.show');
Route::get('/matches/{id}', [MatchesController::class, 'show'])->name('matches.show');
Route::put('/matches/{id}', [MatchesController::class, 'update'])->name('matches.update');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/games/store', [AdminController::class, 'storeGame'])->name('admin.games.store');
    Route::post('/admin/games/update', [AdminController::class, 'updateGame'])->name('admin.games.update');
    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/posts/my', [PostController::class, 'myPosts'])->name('posts.my');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::post('/posts/{id}/update', [PostController::class, 'update'])->name('posts.update');
});

Route::post('/admin/games/delete', [AdminController::class, 'deleteGame'])->name('admin.games.delete');

Route::post('/teams/{team}/remove-member/{user}', [TeamController::class, 'removeMember'])->name('teams.removeMember');   
require __DIR__.'/auth.php';