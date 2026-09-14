<?php
use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;
Route::get('/', [LessonController::class, 'index'])->name('home');
Route::get('/belajar/{slug}', [LessonController::class, 'show'])->name('lessons.show');
