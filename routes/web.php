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

// Properties
Route::middleware(['auth:sanctum', 'verified'])->get('/properties', [\App\Http\Controllers\PropertyController::class, 'index'])->name('properties.index');
Route::middleware(['auth:sanctum', 'verified'])->get('/properties/create', [\App\Http\Controllers\PropertyController::class, 'create'])->name('properties.create');
Route::middleware(['auth:sanctum', 'verified'])->get('/properties/{id}', [\App\Http\Controllers\PropertyController::class, 'show'])->name('properties.show');
Route::middleware(['auth:sanctum', 'verified'])->get('/properties/{id}/edit', [\App\Http\Controllers\PropertyController::class, 'edit'])->name('properties.edit');
Route::middleware(['auth:sanctum', 'verified'])->put('/properties/{id}', [\App\Http\Controllers\PropertyController::class, 'update'])->name('properties.update');
Route::middleware(['auth:sanctum', 'verified'])->delete('/properties/{id}', [\App\Http\Controllers\PropertyController::class, 'destroy'])->name('properties.destroy');
Route::middleware(['auth:sanctum', 'verified'])->post('/properties', [\App\Http\Controllers\PropertyController::class, 'store'])->name('properties.store');

// Property Memberships
Route::middleware(['auth:sanctum', 'verified'])->get('users/{userId}/properties', [\App\Http\Controllers\UserPropertyController::class, 'index'])->name('user-properties.index');
Route::middleware(['auth:sanctum', 'verified'])->post('users/{userId}/properties', [\App\Http\Controllers\UserPropertyController::class, 'store'])->name('user-properties.store');
Route::middleware(['auth:sanctum', 'verified'])->delete('users/{userId}/properties/{id}', [\App\Http\Controllers\UserPropertyController::class, 'destroy'])->name('user-properties.destroy');

// User Bookings
Route::middleware(['auth:sanctum', 'verified'])->get('users/{userId}/bookings', [\App\Http\Controllers\UserBookingController::class, 'index'])->name('user-bookings.index');
Route::middleware(['auth:sanctum', 'verified'])->get('users/{userId}/bookings/create', [\App\Http\Controllers\UserBookingController::class, 'create'])->name('user-bookings.create');
Route::middleware(['auth:sanctum', 'verified'])->post('users/{userId}/bookings', [\App\Http\Controllers\UserBookingController::class, 'store'])->name('user-bookings.store');
Route::middleware(['auth:sanctum', 'verified'])->delete('users/{userId}/bookings/{id}', [\App\Http\Controllers\UserBookingController::class, 'destroy'])->name('user-bookings.destroy');

// Calendars
Route::middleware(['auth:sanctum', 'verified'])->get('properties/{propertyId}/calendars', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendars.index');
Route::middleware(['auth:sanctum', 'verified'])->get('properties/{propertyId}/calendars/create', [\App\Http\Controllers\CalendarController::class, 'create'])->name('calendars.create');
Route::middleware(['auth:sanctum', 'verified'])->post('properties/{propertyId}/calendars', [\App\Http\Controllers\CalendarController::class, 'store'])->name('calendars.store');
Route::middleware(['auth:sanctum', 'verified'])->get('properties/{propertyId}/calendars/{id}', [\App\Http\Controllers\CalendarController::class, 'show'])->name('calendars.show');
Route::middleware(['auth:sanctum', 'verified'])->delete('properties/{propertyId}/calendars/{id}', [\App\Http\Controllers\CalendarController::class, 'destroy'])->name('calendars.destroy');
Route::middleware(['auth:sanctum', 'verified'])->get('properties/{propertyId}/calendars/{id}/edit', [\App\Http\Controllers\CalendarController::class, 'edit'])->name('calendars.edit');
Route::middleware(['auth:sanctum', 'verified'])->put('properties/{propertyId}/calendars/{id}', [\App\Http\Controllers\CalendarController::class, 'update'])->name('calendars.update');

// Slots
Route::middleware(['auth:sanctum', 'verified'])->get('calendars/{calendarId}/slots', [\App\Http\Controllers\SlotController::class, 'index'])->name('slots.index');
Route::middleware(['auth:sanctum', 'verified'])->post('calendars/{calendarId}/slots', [\App\Http\Controllers\SlotController::class, 'store'])->name('slots.store');
Route::middleware(['auth:sanctum', 'verified'])->get('calendars/{calendarId}/slots/{id}', [\App\Http\Controllers\SlotController::class, 'show'])->name('slots.show');
Route::middleware(['auth:sanctum', 'verified'])->delete('calendars/{calendarId}/slots/{id}', [\App\Http\Controllers\SlotController::class, 'destroy'])->name('slots.destroy');
Route::middleware(['auth:sanctum', 'verified'])->get('calendars/{calendarId}/slots/{id}/edit', [\App\Http\Controllers\SlotController::class, 'edit'])->name('slots.edit');
Route::middleware(['auth:sanctum', 'verified'])->put('calendars/{calendarId}/slots/{id}', [\App\Http\Controllers\SlotController::class, 'update'])->name('slots.update');

// Pass
Route::middleware(['auth:sanctum', 'verified'])->get('calendars/{calendarId}/passes', [\App\Http\Controllers\PassController::class, 'index'])->name('passes.index');
Route::middleware(['auth:sanctum', 'verified'])->get('calendars/{calendarId}/passes/{id}', [\App\Http\Controllers\PassController::class, 'show'])->name('passes.show');

// Bookings

// Team Admin
Route::middleware(['auth:sanctum', 'verified'])->get('/bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
Route::middleware(['auth:sanctum', 'verified'])->post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
Route::middleware(['auth:sanctum', 'verified'])->get('/bookings/create', [\App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
Route::middleware(['auth:sanctum', 'verified'])->get('/bookings/{id}/show', [\App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
Route::middleware(['auth:sanctum', 'verified'])->delete('/bookings/{id}', [\App\Http\Controllers\BookingController::class, 'destroy'])->name('bookings.destroy');
Route::middleware(['auth:sanctum', 'verified'])->delete('/booking-slot/{id}', [\App\Http\Controllers\BookingController::class, 'destroyBookingSlot'])->name('bookings.destroyBookingSlot');


