<?php

use App\Http\Controllers\AdminWorldCupController;
use App\Http\Controllers\WorldCupController;
use Illuminate\Support\Facades\Route;


Route::get('/worldcup', [WorldCupController::class, 'index'])->name('worldcup.index');

Route::post('/worldcup/matches/{match}/prediction', [WorldCupController::class, 'submitPrediction'])
    ->middleware('auth')
    ->name('worldcup.predictions.submit');

Route::prefix('admin/worldcup')
    ->middleware(['auth']) // اگر گارد/میدل‌ور ادمین دارید، اینجا جایگزین کنید: auth:admin یا can:admin
    ->name('admin.worldcup.')
    ->group(function () {
        Route::get('/', [AdminWorldCupController::class, 'dashboard'])->name('dashboard');

        Route::get('/matches', [AdminWorldCupController::class, 'matches'])->name('matches');
        Route::get('/matches/create', [AdminWorldCupController::class, 'createMatch'])->name('matches.create');
        Route::post('/matches', [AdminWorldCupController::class, 'storeMatch'])->name('matches.store');
        Route::get('/matches/{match}/edit', [AdminWorldCupController::class, 'editMatch'])->name('matches.edit');
        Route::put('/matches/{match}', [AdminWorldCupController::class, 'updateMatch'])->name('matches.update');
        Route::post('/matches/{match}/result', [AdminWorldCupController::class, 'submitResult'])->name('matches.result');
        Route::post('/matches/{match}/recalculate', [AdminWorldCupController::class, 'recalculateMatch'])->name('matches.recalculate');

        Route::get('/leaderboard', [AdminWorldCupController::class, 'leaderboard'])->name('leaderboard');

        Route::get('/scoring-rules', [AdminWorldCupController::class, 'scoringRules'])->name('scoring-rules');
        Route::post('/scoring-rules', [AdminWorldCupController::class, 'storeScoringRule'])->name('scoring-rules.store');

        Route::get('/prizes', [AdminWorldCupController::class, 'prizes'])->name('prizes');
        Route::post('/prizes', [AdminWorldCupController::class, 'storePrize'])->name('prizes.store');

        Route::get('/faqs', [AdminWorldCupController::class, 'faqs'])->name('faqs');
        Route::post('/faqs', [AdminWorldCupController::class, 'storeFaq'])->name('faqs.store');
    });
