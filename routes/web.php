<?php

use App\Http\Controllers\ImagePostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\TeamController;
use App\Models\ImagePost;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Teams
    Route::resource('teams', TeamController::class);
    Route::post('/teams/{team}/join', [TeamController::class, 'join']);
    Route::post('/teams/{team}/unjoin', [TeamController::class, 'unjoin']);
    Route::post('/teams/{team}/role', [TeamController::class, 'role']);

    // Posts
    Route::get('/posts/image/create',[ImagePostController::class,'create'])->name('imagepost.create');
    Route::post('/posts/image/store',[ImagePostController::class,'store'])->name('imagepost.store');

});

// Public user account and posts.
Route::prefix('@{userSlug}')->group(function () {
    Route::get('/{imageId}/{imagePostSlug}',[ImagePostController::class,'show'])->name("imagepost.show");
    Route::delete('/{imageId}/{imagePostSlug}',[ImagePostController::class,'delete'])->name("imagepost.delete");
});

// Media
Route::get('/media/img/{path}',[ImagePostController::class,'getImageFile']);

// Sociallite routes
Route::get('/auth/redirect', [SocialiteController::class, "googleRedirect"])->name('socialite-google-redirect');
Route::get('/auth/callback', [SocialiteController::class, "googleCallback"])->name('socialite-google-callback');

require __DIR__ . '/auth.php';
