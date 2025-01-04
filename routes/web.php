<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [GameController::class, 'index'])->name('game');
Route::post('/random-move', [GameController::class, 'generateRandomMove']);
Route::get('/player-select', [GameController::class, 'selectUser']);
Route::post('/player-select', [GameController::class, 'setPlayer']);
Route::get('/new-match', [GameController::class, 'newMatch']);
Route::get('/match/{id}', [GameController::class, 'match']);

Route::post('/manual-move', [GameController::class, 'playerManualMove']);
