<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// --------------------
// Public routes
// --------------------

// Homepage - list of posts
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// /posts par bhi list hi dikhayenge (agar kahin route('posts') ya /posts use ho raha ho)
Route::get('/posts', [PostController::class, 'index'])->name('posts');

// --------------------
// Protected routes (sirf logged in users)
// --------------------
Route::middleware('auth')->group(function () {

    // Create
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Edit / Update / Delete
    Route::get('/posts/{slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{slug}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{slug}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------
// Single post show (slug) - PUBLIC
// IMPORTANT: isko /posts/create, /posts, /posts/{slug}/edit ke BAAD rakho
// --------------------
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

// --------------------
// Dashboard
// --------------------
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --------------------
// Google OAuth routes
// --------------------
Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
            'avatar' => $googleUser->getAvatar(),
            'password' => bcrypt(Str::random(16)),
        ]
    );

    Auth::login($user);

    return redirect()->route('posts.index');
});

require __DIR__ . '/auth.php';
