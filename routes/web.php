<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Marketing\QueueController;
use App\Http\Controllers\Marketing\CallController;
use App\Http\Controllers\Sales\AppointmentController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\LeadSourceTypeController;
use App\Http\Controllers\Admin\LeadSourceController;
use App\Http\Controllers\NoteController;

// Redirect home ? dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// ========================
// Authenticated app routes
// ========================
Route::middleware(['auth', 'update.last.activity'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Contacts
    Route::resource('contacts', ContactController::class);
    Route::resource('appointments', AppointmentController::class)->only(['edit','update','destroy']);
// Nested for notes on a contact
Route::post('/contacts/{contact}/notes', [NoteController::class, 'store'])->name('contacts.notes.store');
Route::delete('/contacts/{contact}/notes/{note}', [NoteController::class, 'destroy'])->name('contacts.notes.destroy');

    // Queues
    Route::resource('queues', QueueController::class);

    // Appointments (page + lazy-load JSON)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/table', [AppointmentController::class, 'table'])->name('appointments.table');


    // Call logging
    Route::post('calls/{contact}/start', [CallController::class, 'start'])->name('calls.start');
    Route::post('calls/{call}/end', [CallController::class, 'end'])->name('calls.end');

    // Reports
    Route::get('reports/calls', [ReportController::class, 'calls'])->name('reports.calls');
    Route::get('reports/appointments', [ReportController::class, 'appointments'])->name('reports.appointments');

    // Live presence
    Route::get('/current-calls', function () {
        return \App\Models\CurrentCall::with('user', 'contact')->get();
    })->name('current.calls');



// SETTINGS
Route::prefix('settings')->name('settings.')->middleware('auth')->group(function () {
    // Dispositions
    Route::resource('dispositions', \App\Http\Controllers\Settings\DispositionController::class)->except(['show']);

    // Result Ran list
    Route::resource('result-rans', \App\Http\Controllers\Settings\ResultRanController::class)->except(['show']);

    // Confirmation Results list
    Route::resource('results', \App\Http\Controllers\Settings\ConfirmationResultController::class)->except(['show']);

    // APIs / Connections
    Route::get('apis', [\App\Http\Controllers\Settings\ApiController::class, 'edit'])->name('apis.edit');
    Route::post('apis', [\App\Http\Controllers\Settings\ApiController::class, 'update'])->name('apis.update');
});


// Geocode a contact (server-side with Google Maps)
Route::post('/contacts/{contact}/geocode', [\App\Http\Controllers\GeocodeController::class, 'geocode'])
    ->name('contacts.geocode')
    ->middleware('auth');



});


Route::middleware(['web','auth','update.last.activity'])->group(function () {
    // Add store (and keep edit/update/destroy if you already added them)
    Route::resource('appointments', AppointmentController::class)
         ->only(['store','edit','update','destroy']);
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])
        ->name('users.index');
    Route::resource('dispositions', \App\Http\Controllers\Settings\DispositionController::class)->only(['index','create','store','edit','update','destroy']);

    Route::resource('result-rans', \App\Http\Controllers\Settings\ResultRanController::class)->only(['index','create','store','edit','update','destroy']);

    Route::resource('results', \App\Http\Controllers\Settings\ConfirmResultController::class)->only(['index','create','store','edit','update','destroy']);

    Route::get('apis', [\App\Http\Controllers\Settings\ApiController::class,'edit'])->name('apis.edit');
    Route::put('apis', [\App\Http\Controllers\Settings\ApiController::class,'update'])->name('apis.update');
});


// ====================
// Admin-only routes
// ====================
Route::middleware(['auth', 'role:Admin'])->group(function () {
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Lead Source Types (e.g., Home Show, Web Form, CSV Import)
    Route::resource('lead-source-types', LeadSourceTypeController::class)
        ->except(['show']);

    // Lead Sources (specific sources linked to a type)
    Route::resource('lead-sources', LeadSourceController::class)
        ->except(['show']);

    // Sanity route to test RBAC
    Route::get('/admin/ping', fn () => 'ADMIN OK')->name('admin.ping');
 Route::get('/users', [UserController::class, 'index'])->name('users.index');
    // Route::resource('users', UserController::class); // if you want full CRUD
});

// ====================
// Auth scaffolding
// ====================
require __DIR__.'/auth.php';
