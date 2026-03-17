<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, HomeController, ProfileController, ConversationController, NotificationController};
use App\Http\Controllers\Admin\{DashboardController as AdminDashboard, UserController as AdminUser, RideController as AdminRide, ReportController as AdminReport, PromoController as AdminPromo};
use App\Http\Controllers\Driver\{DashboardController as DriverDashboard, RideController as DriverRide, VehicleController as DriverVehicle, BookingController as DriverBooking};
use App\Http\Controllers\Passenger\{DashboardController as PassengerDashboard, RideController as PassengerRide, BookingController as PassengerBooking, WalletController as PassengerWallet};

// ─── Public routes ───────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/connexion',       [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion',      [AuthController::class, 'login']);
    Route::get('/inscription',     [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription',    [AuthController::class, 'register']);
});
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Authenticated routes ─────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profil/modifier',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil',                   [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe',      [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications',                        [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/lu',     [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/tout-lire',             [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // Conversations
    Route::get('/messages',                             [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/messages/{conversation}',              [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/messages/{conversation}',             [ConversationController::class, 'sendMessage'])->name('conversations.send');
    Route::post('/reservations/{booking}/messages',     [ConversationController::class, 'store'])->name('conversations.create');

    // ─── ADMIN ───────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/tableau-de-bord',          [AdminDashboard::class, 'index'])->name('dashboard');

        Route::get('/utilisateurs',             [AdminUser::class, 'index'])->name('users.index');
        Route::get('/utilisateurs/{user}',      [AdminUser::class, 'show'])->name('users.show');
        Route::post('/utilisateurs/{user}/statut',  [AdminUser::class, 'toggleStatus'])->name('users.toggle');
        Route::post('/utilisateurs/{user}/verifier',[AdminUser::class, 'verifyDriver'])->name('users.verify');
        Route::delete('/utilisateurs/{user}',   [AdminUser::class, 'destroy'])->name('users.destroy');

        Route::get('/trajets',                  [AdminRide::class, 'index'])->name('rides.index');
        Route::get('/trajets/{ride}',           [AdminRide::class, 'show'])->name('rides.show');
        Route::post('/trajets/{ride}/annuler',  [AdminRide::class, 'cancel'])->name('rides.cancel');

        Route::get('/signalements',             [AdminReport::class, 'index'])->name('reports.index');
        Route::put('/signalements/{report}',    [AdminReport::class, 'update'])->name('reports.update');

        Route::get('/promos',                   [AdminPromo::class, 'index'])->name('promos.index');
        Route::get('/promos/creer',             [AdminPromo::class, 'create'])->name('promos.create');
        Route::post('/promos',                  [AdminPromo::class, 'store'])->name('promos.store');
        Route::post('/promos/{promo}/basculer', [AdminPromo::class, 'toggle'])->name('promos.toggle');
        Route::delete('/promos/{promo}',        [AdminPromo::class, 'destroy'])->name('promos.destroy');
    });

    // ─── DRIVER ──────────────────────────────────────────────────
    Route::middleware('role:driver')->prefix('conducteur')->name('driver.')->group(function () {
        Route::get('/tableau-de-bord',              [DriverDashboard::class, 'index'])->name('dashboard');

        Route::get('/trajets',                      [DriverRide::class, 'index'])->name('rides.index');
        Route::get('/trajets/nouveau',              [DriverRide::class, 'create'])->name('rides.create');
        Route::post('/trajets',                     [DriverRide::class, 'store'])->name('rides.store');
        Route::get('/trajets/{ride}',               [DriverRide::class, 'show'])->name('rides.show');
        Route::get('/trajets/{ride}/modifier',      [DriverRide::class, 'edit'])->name('rides.edit');
        Route::put('/trajets/{ride}',               [DriverRide::class, 'update'])->name('rides.update');
        Route::post('/trajets/{ride}/annuler',      [DriverRide::class, 'cancel'])->name('rides.cancel');

        Route::get('/vehicules',                    [DriverVehicle::class, 'index'])->name('vehicles.index');
        Route::get('/vehicules/nouveau',            [DriverVehicle::class, 'create'])->name('vehicles.create');
        Route::post('/vehicules',                   [DriverVehicle::class, 'store'])->name('vehicles.store');
        Route::delete('/vehicules/{vehicle}',       [DriverVehicle::class, 'destroy'])->name('vehicles.destroy');

        Route::get('/reservations',                 [DriverBooking::class, 'index'])->name('bookings.index');
        Route::post('/reservations/{booking}/confirmer', [DriverBooking::class, 'confirm'])->name('bookings.confirm');
        Route::post('/reservations/{booking}/refuser',   [DriverBooking::class, 'reject'])->name('bookings.reject');
    });

    // ─── PASSENGER ───────────────────────────────────────────────
    Route::middleware('role:passenger')->prefix('passager')->name('passenger.')->group(function () {
        Route::get('/tableau-de-bord',              [PassengerDashboard::class, 'index'])->name('dashboard');

        Route::get('/rechercher',                   [PassengerRide::class, 'index'])->name('rides.index');
        Route::get('/trajets/{ride}',               [PassengerRide::class, 'show'])->name('rides.show');

        Route::get('/reservations',                 [PassengerBooking::class, 'index'])->name('bookings.index');
        Route::get('/reservations/{booking}',       [PassengerBooking::class, 'show'])->name('bookings.show');
        Route::post('/trajets/{ride}/reserver',     [PassengerBooking::class, 'store'])->name('bookings.store');
        Route::post('/reservations/{booking}/annuler', [PassengerBooking::class, 'cancel'])->name('bookings.cancel');
        Route::post('/reservations/{booking}/evaluer', [PassengerBooking::class, 'review'])->name('bookings.review');

        Route::get('/portefeuille',                 [PassengerWallet::class, 'index'])->name('wallet.index');
        Route::post('/portefeuille/recharger',      [PassengerWallet::class, 'topup'])->name('wallet.topup');
    });
});
