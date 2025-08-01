<?php
// routes/web.php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ItUserController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\EmailTicketController;
use App\Http\Controllers\BulkImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // Equipment Management
    Route::resource('equipment', EquipmentController::class);
    Route::get('equipment/{equipment}/download-invoice', [EquipmentController::class, 'downloadInvoice'])
        ->name('equipment.download-invoice');

    // Equipment Types
    Route::resource('equipment-types', EquipmentTypeController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // IT Users
    Route::resource('it-users', ItUserController::class);
    Route::get('it-users/{itUser}/documents', [ItUserController::class, 'documents'])
        ->name('it-users.documents');
    Route::post('it-users/{itUser}/documents', [ItUserController::class, 'uploadDocument'])
        ->name('it-users.upload-document');

    // Assignments
    Route::resource('assignments', AssignmentController::class)->except(['edit', 'update']);
    Route::get('assignments/{assignment}/return', [AssignmentController::class, 'returnEquipment'])
        ->name('assignments.return');
    Route::post('assignments/{assignment}/return', [AssignmentController::class, 'processReturn'])
        ->name('assignments.process-return');
    Route::get('assignments/{assignment}/download', [AssignmentController::class, 'downloadDocument'])
        ->name('assignments.download');
    Route::post('assignments/{assignment}/mark-signed', [AssignmentController::class, 'markSigned'])
        ->name('assignments.mark-signed');

    // Maintenance
    Route::resource('maintenance', MaintenanceController::class);
    Route::get('maintenance-calendar', [MaintenanceController::class, 'calendar'])
        ->name('maintenance.calendar');
    Route::post('maintenance/{maintenance}/start', [MaintenanceController::class, 'startMaintenance'])
        ->name('maintenance.start');
    Route::post('maintenance/{maintenance}/complete', [MaintenanceController::class, 'completeMaintenance'])
        ->name('maintenance.complete');

    // Contracts
    Route::resource('contracts', ContractController::class);
    Route::get('contracts/{contract}/download', [ContractController::class, 'downloadFile'])
        ->name('contracts.download');
    Route::get('contracts/{contract}/renew', [ContractController::class, 'renewContract'])
        ->name('contracts.renew');

    // Email Tickets
    Route::resource('email-tickets', EmailTicketController::class)->only(['index', 'show']);
    Route::post('email-tickets/{emailTicket}/assign', [EmailTicketController::class, 'assign'])
        ->name('email-tickets.assign');
    Route::post('email-tickets/{emailTicket}/resolve', [EmailTicketController::class, 'resolve'])
        ->name('email-tickets.resolve');
    Route::post('email-tickets/{emailTicket}/close', [EmailTicketController::class, 'close'])
        ->name('email-tickets.close');

    // Bulk Imports
    Route::resource('bulk-imports', BulkImportController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('bulk-imports/template/{type}', [BulkImportController::class, 'downloadTemplate'])
        ->name('bulk-imports.template');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/equipment', [ReportController::class, 'equipment'])->name('reports.equipment');
    Route::get('reports/assignments', [ReportController::class, 'assignments'])->name('reports.assignments');
    Route::get('reports/maintenance', [ReportController::class, 'maintenance'])->name('reports.maintenance');
    Route::get('reports/contracts', [ReportController::class, 'contracts'])->name('reports.contracts');
    Route::get('reports/dashboard', [ReportController::class, 'dashboard'])->name('reports.dashboard');

    // User Documents
    Route::get('documents/{document}/download', [UserDocumentController::class, 'download'])
        ->name('documents.download');
    Route::delete('documents/{document}', [UserDocumentController::class, 'destroy'])
        ->name('documents.destroy');
    Route::post('documents/{document}/mark-signed', [UserDocumentController::class, 'markSigned'])
        ->name('documents.mark-signed');
});

//require __DIR__.'/auth.php';
