<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| These routes are accessible without authentication.
| Visitors can view the homepage and individual blog posts.
|
*/

// Homepage - list of all posts
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// /posts also shows the list of posts
Route::get('/posts', [PostController::class, 'index'])->name('posts');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| These routes require the user to be logged in.
| Users can create, edit, update, and delete their posts, and manage profile.
|
*/

Route::middleware('auth')->group(function () {

    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my');

    // Create new post
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Edit / update / delete existing post
    Route::get('/posts/{slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{slug}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{slug}', [PostController::class, 'destroy'])->name('posts.destroy');

    // User profile (Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Single Post (Public)
|--------------------------------------------------------------------------
|
| Show a single post by its slug.
| This route must be placed after /posts/create and /posts/{slug}/edit.
|
*/

Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
|
| Simple dashboard view for authenticated and verified users.
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Google OAuth Routes
|--------------------------------------------------------------------------
|
| Social login using Google via Laravel Socialite.
| Handles redirect and callback for Google authentication.
|
*/

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {

    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name'    => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
            'avatar'  => $googleUser->getAvatar(),
            'password'=> bcrypt(Str::random(16)),
        ]
    );

    // 🔥 Auto make admin if email matches
    if ($user->email === 'sumitkanth7@gmail.com') {
        $user->is_admin = true;
        $user->save();
    }

    Auth::login($user);

    return redirect()->route('posts.index');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
