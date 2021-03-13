<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

//Route::middleware(['auth:sanctum', 'verified'])->get('/calendars', function () {
//    return Inertia::render('Calendar');
//})->name('calendar');

// Slots
Route::middleware(['auth:sanctum', 'verified'])->get('/calendars/{id}/slots', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendars.index');
Route::middleware(['auth:sanctum', 'verified'])->post('/slots', [\App\Http\Controllers\SlotController::class, 'store'])->name('slots.store');
Route::middleware(['auth:sanctum', 'verified'])->delete('/slots/{id}', [\App\Http\Controllers\SlotController::class, 'destroy'])->name('slots.destroy');

// Calendars
Route::middleware(['auth:sanctum', 'verified'])->get('/calendars', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendars.index');
Route::middleware(['auth:sanctum', 'verified'])->get('/calendars/{id}/edit', [\App\Http\Controllers\CalendarController::class, 'edit'])->name('calendars.edit');
Route::middleware(['auth:sanctum', 'verified'])->put('/calendars/{id}/update', [\App\Http\Controllers\CalendarController::class, 'update'])->name('calendars.update');

// Bookings
Route::middleware(['auth:sanctum', 'verified'])->get('/bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
Route::middleware(['auth:sanctum', 'verified'])->post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
Route::middleware(['auth:sanctum', 'verified'])->get('/bookings/create', [\App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
Route::middleware(['auth:sanctum', 'verified'])->delete('/bookings/{id}', [\App\Http\Controllers\BookingController::class, 'destroy'])->name('bookings.destroy');
