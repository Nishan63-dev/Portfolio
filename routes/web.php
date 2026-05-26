<?php

use App\Http\Controllers\Api\ExpenseController as ApiExpenseController;
use App\Http\Controllers\Api\IncomeController as ApiIncomeController;
use App\Http\Controllers\Api\PayrollController as ApiPayrollController;
use App\Http\Controllers\Api\ReportController as ApiReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/incomes', [IncomeController::class, 'index'])->name('incomes');
    Route::get('/incomes/create', [IncomeController::class, 'create'])->name('incomes.create');
    Route::post('/incomes', [IncomeController::class, 'store'])->name('incomes.store');
    Route::put('/incomes/{income}', [IncomeController::class, 'update'])->name('incomes.update');
    Route::get('/incomes/{income}', [IncomeController::class, 'show'])->name('incomes.show');
    Route::get('/incomes/{income}/edit', [IncomeController::class, 'edit'])->name('incomes.edit');

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');

    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls');
    Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create');
    Route::post('/payrolls', [PayrollController::class, 'store'])->name('payrolls.store');
    Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update');
    Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show');
    Route::get('/payrolls/{payroll}/edit', [PayrollController::class, 'edit'])->name('payrolls.edit');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    Route::prefix('api')->middleware('throttle:60,1')->group(function () {
        Route::get('/dashboard', [ApiReportController::class, 'dashboard']);
        Route::get('/reports/summary', [ApiReportController::class, 'summary']);

        Route::get('/incomes', [ApiIncomeController::class, 'index']);
        Route::post('/incomes', [ApiIncomeController::class, 'store']);
        Route::put('/incomes/{income}', [ApiIncomeController::class, 'update']);

        Route::get('/expenses', [ApiExpenseController::class, 'index']);
        Route::post('/expenses', [ApiExpenseController::class, 'store']);
        Route::put('/expenses/{expense}', [ApiExpenseController::class, 'update']);

        Route::get('/payrolls', [ApiPayrollController::class, 'index']);
        Route::post('/payrolls', [ApiPayrollController::class, 'store']);
        Route::put('/payrolls/{payroll}', [ApiPayrollController::class, 'update']);
    });

    Route::get('/export/incomes', [ApiIncomeController::class, 'export'])->name('export.incomes');
    Route::get('/export/expenses', [ApiExpenseController::class, 'export'])->name('export.expenses');
    Route::get('/export/payrolls', [ApiPayrollController::class, 'export'])->name('export.payrolls');

    Route::get('/pdf/receipt/{income}', [PdfController::class, 'incomeReceipt'])->name('pdf.receipt');
    Route::get('/pdf/summary', [PdfController::class, 'financialSummary'])->name('pdf.summary');
});
