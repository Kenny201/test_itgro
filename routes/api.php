<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorController::class, 'getList'])->name('authors.getList');
    Route::get('/{id}', [AuthorController::class, 'findById'])->name('authors.findById');
    Route::post('/', [AuthorController::class, 'create'])->name('authors.create');
    Route::put('/{id}', [AuthorController::class, 'update'])->name('authors.update');
    Route::delete('/{id}', [AuthorController::class, 'delete'])->name('authors.delete');

});


Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'getList'])->name('books.getList');
    Route::get('/{id}', [BookController::class, 'findByID'])->name('books.findById');
    Route::post('/', [BookController::class, 'create'])->name('books.create');
    Route::put('/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/{id}', [BookController::class, 'delete'])->name('books.delete');

    Route::get('/{bookId}/chapters', [ChapterController::class, 'getList'])->name('chapters.getList');
    Route::get('/{bookId}/chapters/{id}', [ChapterController::class, 'findByID'])->name('chapters.findById');
    Route::post('/{bookId}/chapters', [ChapterController::class, 'create'])->name('chapters.create');
    Route::put('/{bookId}/chapters/{id}', [ChapterController::class, 'update'])->name('chapters.update');
    Route::delete('/{bookId}/chapters/{id}', [ChapterController::class, 'delete'])->name('chapters.delete');
});
