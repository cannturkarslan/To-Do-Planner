<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);

Route::get('/test-assignment', function () {
    $taskAssignment = new App\Services\TaskAssignmentService();
    return $taskAssignment->assignTasksToDevelopers();
});