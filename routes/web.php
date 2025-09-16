<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//Login Admin
Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AdminController::class, 'login'])->name('login.submit');

Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

