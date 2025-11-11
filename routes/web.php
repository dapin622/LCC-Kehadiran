<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TambahMemberController;

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


Route::middleware(['admin.auth'])->group(function () {

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard/member', [DashboardController::class, 'getMembers'])->name('admin.dashboard.member');


//member
Route::get('/admin/member', [MemberController::class, 'index'])->name('admin.member'); 
// Route::get('/admin/member/index', [MemberController::class, 'index'])->name('admin.member');
// Route::get('/admin/member/create', [MemberController::class, 'create'])->name('admin.member.create');
Route::post('/admin/member/store', [MemberController::class, 'store'])->name('admin.member.store');
// Route::get('/admin/member/{id}/edit', [MemberController::class, 'edit'])->name('admin.member.edit');
Route::put('/admin/member/{id}', [MemberController::class, 'update'])->name('admin.member.update');
Route::delete('/admin/member/{id}', [MemberController::class, 'destroy'])->name('admin.member.destroy');


//kelas
Route::get('/admin/member/class', [ClassRoomController::class, 'index'])->name('admin.member.class');
Route::post('/admin/member/class/store', [ClassRoomController::class, 'store'])->name('admin.member.class.store');
Route::put('/admin/member/class/{id}', [ClassRoomController::class, 'update'])->name('admin.member.class.update');
Route::delete('/admin/member/class/{id}', [ClassRoomController::class, 'destroy'])->name('admin.member.class.destroy');

//sekolah
Route::get('/admin/school', [SchoolController::class, 'index'])->name('admin.sekolah');
Route::get('admin/school/create', [SchoolController::class, 'create'])->name('admin.sekolah.create');
Route::put('/admin/school/{id}', [SchoolController::class, 'update'])->name('admin.sekolah.update');
Route::post('/admin/school/store', [SchoolController::class, 'store'])->name('admin.sekolah.store');
Route::get('/admin/school/filter', [SchoolController::class, 'filter'])->name('admin.sekolah.filter');
Route::delete('/admin/school/{id}', [SchoolController::class, 'destroy'])->name('admin.sekolah.destroy');


Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

});