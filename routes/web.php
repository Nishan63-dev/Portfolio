<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/health', fn () => response()->json(['status' => 'ok']));

Route::get('/resume.pdf', function () {
    return response()->file(public_path('resume.pdf'));
});
