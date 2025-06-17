<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
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
Route::get('/profile/me', function () { return view('profile.myProfile');})->name('profile.me');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});
Route::get('/teams/my', function () {
    return view('Teams.index'); 
})->middleware(['auth'])->name('teams.my');

Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');


Route::get('/teams/{id}/details', function ($id) {
    
    return view('Teams.details', ['id' => $id]);
})->middleware(['auth'])->name('teams.details');

Route::get('/profile/me', [ProfileController::class, 'myProfile'])
    ->middleware(['auth'])
    ->name('profile.me');


Route::get('/matches', function () {
    return view('Matches.index');
})->middleware(['auth'])->name('matches.index');
Route::post('/posts/{id}/apply', [PostController::class, 'apply'])->name('posts.apply');
Route::post('/requests/{id}/accept', [PostController::class, 'acceptRequest'])->name('posts.acceptRequest');
Route::post('/requests/{id}/reject', [PostController::class, 'rejectRequest'])->name('posts.rejectRequest');

Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::post('/posts/{id}/apply', [PostController::class, 'apply'])->name('posts.apply');
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
require __DIR__.'/auth.php';