<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Users\UserController;

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
    return view('auth.login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');


Route::get('/users/nearby', [UserController::class, 'getNearbyUsers'])
    ->name('api.user.nearbyusers');

    Route::get('/loggedin/user', [UserController::class, 'getLoggedInUser'])
    ->name('api.user.loggedin');
   