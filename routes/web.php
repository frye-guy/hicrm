<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\Marketing\QueueController;
use App\Http\Controllers\Marketing\CallController;
use App\Http\Controllers\Sales\AppointmentController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\LeadSourceTypeController;
use App\Http\Controllers\Admin\LeadSourceController;

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
});


Route::middleware(['web','auth','update.last.activity'])->group(function () {
    // Add store (and keep edit/update/destroy if you already added them)
    Route::resource('appointments', AppointmentController::class)
         ->only(['store','edit','update','destroy']);
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
});

// ====================
// Auth scaffolding
// ====================
require __DIR__.'/auth.php';
