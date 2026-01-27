<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\UserAbsensiController;
use App\Http\Controllers\RiwayatAbsensiController;

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

//Login

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth:admin'])->group(function () {

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard/member', [DashboardController::class, 'getMembers'])->name('admin.dashboard.member');


//member
Route::get('/admin/member', [MemberController::class, 'index'])->name('admin.member'); 
Route::post('/admin/member/store', [MemberController::class, 'store'])->name('admin.member.store');
Route::put('/admin/member/{id}', [MemberController::class, 'update'])->name('admin.member.update');
Route::delete('/admin/member/{id}', [MemberController::class, 'destroy'])->name('admin.member.destroy');
Route::get('/admin/member/export', [MemberController::class, 'exportExcel'])->name('admin.member.export');


//kelas
Route::get('/admin/member/class', [ClassRoomController::class, 'index'])->name('admin.member.class');
Route::post('/class/store', [ClassRoomController::class, 'store'])->name('class.store');
Route::put('/class/{id}', [ClassRoomController::class, 'update'])->name('class.update');
Route::delete('/class/{id}', [ClassRoomController::class, 'destroy'])->name('class.destroy');

//sekolah
Route::get('/admin/school', [SchoolController::class, 'index'])->name('admin.sekolah');
Route::get('admin/school/create', [SchoolController::class, 'create'])->name('admin.sekolah.create');
Route::put('/admin/school/{id}', [SchoolController::class, 'update'])->name('admin.sekolah.update');
Route::post('/admin/school/store', [SchoolController::class, 'store'])->name('admin.sekolah.store');
Route::get('/admin/school/filter', [SchoolController::class, 'filter'])->name('admin.sekolah.filter');
Route::delete('/admin/school/{id}', [SchoolController::class, 'destroy'])->name('admin.sekolah.destroy');
Route::get('/admin/sekolah/export', [SchoolController::class, 'exportExcel'])->name('admin.sekolah.export');

//event
Route::get('/admin/event', [EventController::class, 'index'])->name('admin.event');
Route::post('/admin/event', [EventController::class, 'store'])->name('admin.event.store');
Route::put('/admin/event/{id}', [EventController::class, 'update'])->name('admin.event.update');
Route::delete('/admin/event/{id}', [EventController::class, 'destroy'])->name('admin.event.destroy');

//event participant
Route::get('/admin/event/{event}/participants', [EventParticipantController::class, 'index'])->name('admin.event_participants');

//team
Route::post('/team/store', [TeamController::class, 'store'])->name('team.store');
Route::put('/team/update/{id}', [TeamController::class, 'update'])->name('team.update');
Route::delete('/team/delete/{id}', [TeamController::class, 'destroy'])->name('team.destroy');

//Create Account user
Route::get('/admin/user_account', [UserAccountController::class, 'index'])->name('admin.user_account');
Route::post('/admin/user_account', [UserAccountController::class, 'store'])->name('admin.user_account.store');
Route::put('/admin/users_account/{id}', [UserAccountController::class, 'update'])->name('admin.user_account.update');
Route::delete('/admin/users_account/{id}', [UserAccountController::class, 'destroy'])->name('admin.user_account.destroy');


});


//user buat disini
///......

Route::middleware(['auth:web'])->group(function () {
    Route::get('/user/dashboard', [DashboardUserController::class, 'index'])
        ->name('user.dashboard');

    Route::get('/user/absensi', [UserAbsensiController::class, 'index'])
        ->name('user.absensi');
    Route::post('/user/absensi/submit', [UserAbsensiController::class, 'submit'])
        ->name('user.absensi.submit');

    Route::get('/user/absensi/{eventId}/detail', [UserAbsensiController::class, 'detail'])
    ->name('user.absensi.detail');

    Route::get('/user/riwayat', [RiwayatAbsensiController::class, 'index'])
        ->name('user.riwayat');
});

