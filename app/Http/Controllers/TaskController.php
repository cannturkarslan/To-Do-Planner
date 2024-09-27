<?php

namespace App\Http\Controllers;

use App\Services\TaskAssignmentService;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskAssignment;
    public function __construct(TaskAssignmentService $taskAssignment)
    {
        $this->taskAssignment = $taskAssignment;

    }

    public function index() {
        $tasks = Task::all();
        Log::info('Tasks fetched:', ['count' => $tasks->count()]);
    
        $assignmentResults = $this->taskAssignment->assignTasksToDevelopers();
        Log::info('Assignment Results:', [
            'weeks' => $assignmentResults['weeks'],
            'assignments_count' => count($assignmentResults['assignments'])
        ]);
    
        return view('homepage', [
            'tasks' => $tasks,
            'assignmentResults' => $assignmentResults
        ]);
    }
}
