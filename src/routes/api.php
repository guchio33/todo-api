<?php
use App\Http\Controllers\API\BookController;
use Illuminate\Support\Facades\Route;

// Route::apiResource('books', BookController::class);
Route::prefix('books')->group(function () {
  Route::get('/', [BookController::class, 'index']);
  Route::post('/', [BookController::class, 'store']);
});