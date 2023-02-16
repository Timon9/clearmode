<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Models\User;
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
    Route::post('/teams/{team}/join', [TeamController::class,'join']);
    Route::post('/teams/{team}/unjoin', [TeamController::class,'unjoin']);
    Route::post('/teams/{team}/role', [TeamController::class,'role']);

});


Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('socialite-redirect');

Route::get('/auth/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    Log::info("[Socialite] Logged in using Google #".$googleUser->id);

    // $user = User::updateOrCreate([
    //     'googleUser' => $googleUser->id,
    // ], [
    //     'name' => $googleUser->name,
    //     'email' => $googleUser->email,
    //     'github_token' => $googleUser->token,
    //     'github_refresh_token' => $googleUser->refreshToken,
    // ]);

    // Auth::login($user);

    return redirect('/dashboard');
});

require __DIR__.'/auth.php';
