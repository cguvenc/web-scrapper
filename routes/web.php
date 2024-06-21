<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Back\BotController;
use App\Http\Controllers\Back\HepsiburadaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Back\MarketController;
use App\Http\Controllers\Back\ProductController;
use App\Http\Controllers\Back\ProfileController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\NotificationController;
use App\Http\Controllers\Back\WebsiteController;

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

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::post('/', [AuthController::class, 'login'])->name('login.post');
});


Route::middleware(['auth','view'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profilim', [ProfileController::class, 'index'])->name('profile');
    Route::put('profilim', [ProfileController::class, 'profile_update'])->name('profile.update');
    Route::put('profilim/email', [ProfileController::class, 'email_update'])->name('profile.email');
    Route::put('profilim/password', [ProfileController::class, 'password_update'])->name('profile.password');
    Route::get('websites', [WebsiteController::class, 'index'])->name('website');
    Route::post('websites', [WebsiteController::class, 'store'])->name('website.store');
    Route::get('websites/{id}', [WebsiteController::class, 'show'])->name('website.show');
    Route::put('websites/{id}', [WebsiteController::class, 'update'])->name('website.update');
    Route::get('websites/{id}/delete', [WebsiteController::class, 'destroy'])->name('website.delete');
    Route::get('bot', [BotController::class, 'index'])->name('bot.index');
    Route::get('bildirimler', [NotificationController::class, 'notification'])->name('notification.notification');
    Route::get('bildirimler/check', [NotificationController::class, 'check'])->name('notification.check');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
