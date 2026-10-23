<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\Portal\BillController as PortalBillController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Http\Controllers\Portal\PaymentController as PortalPaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->to(Auth::user()->homeRoute())
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::post('/payments/callback', PaymentCallbackController::class)
    ->name('payments.callback');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('students', StudentController::class)->except('show');
        Route::resource('classes', SchoolClassController::class)->except('show');
        Route::resource('academic-years', AcademicYearController::class)
            ->parameters(['academic-years' => 'academicYear'])
            ->except('show');
        Route::resource('fees', FeeController::class)->except('show');

        Route::get('bills', [BillController::class, 'index'])->name('bills.index');
        Route::get('bills/create', [BillController::class, 'create'])->name('bills.create');
        Route::post('bills', [BillController::class, 'store'])->name('bills.store');
        Route::get('bills/generate', [BillController::class, 'generateForm'])->name('bills.generate');
        Route::post('bills/generate', [BillController::class, 'generate'])->name('bills.generate.store');
        Route::delete('bills/{bill}', [BillController::class, 'destroy'])->name('bills.destroy');

        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

Route::prefix('portal')
    ->name('portal.')
    ->middleware(['auth', 'role:student'])
    ->group(function () {
        Route::get('/', PortalDashboardController::class)->name('dashboard');
        Route::get('bills', [PortalBillController::class, 'index'])->name('bills.index');

        Route::get('payments', [PortalPaymentController::class, 'index'])->name('payments.index');
        Route::post('payments/pay', [PortalPaymentController::class, 'pay'])->name('payments.pay');
        Route::get('payments/{payment}', [PortalPaymentController::class, 'show'])->name('payments.show');
        Route::get('payments/{payment}/finish', [PortalPaymentController::class, 'finish'])->name('payments.finish');
        Route::get('payments/{payment}/receipt', [PortalPaymentController::class, 'receipt'])->name('payments.receipt');
    });
