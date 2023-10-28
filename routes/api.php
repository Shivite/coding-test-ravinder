<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->get('/users', [App\Http\Controllers\TaskController::class, 'users']);
Route::middleware('auth:sanctum')->get('/phases/{phase}', [App\Http\Controllers\PhaseController::class, 'show']);
Route::middleware('auth:sanctum')->put('/phases/{phase}', [App\Http\Controllers\PhaseController::class, 'update']);
// wrap taskcontroler default crud routes within a route group and specify the middleware 
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('tasks', 'App\Http\Controllers\TaskController');
});
Route::middleware('auth:sanctum')->put('/Phases/{phase}/mark-tasks-completed', [App\Http\Controllers\PhaseController::class, 'markTasksCompleted']);
