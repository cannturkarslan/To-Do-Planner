<?php

namespace App\Services;
use App\Models\Task;
use App\Models\Developer;

class TaskAssignmentService
{
    public function assignTasksToDevelopers()
    {
        $tasks = Task::orderBy('level', 'desc')->get();
        $developers = Developer::all();
        $weeks = 0;
        $assignments = [];
        $ongoingTasks = [];

        while ($tasks->isNotEmpty() || !empty($ongoingTasks)) {
            $weeks++;
            foreach ($developers as $developer) {
                $remainingHours = 45; // Weekly hour limit

                // Continue ongoing tasks
                if (isset($ongoingTasks[$developer->id])) {
                    $task = $ongoingTasks[$developer->id]['task'];
                    $remainingTime = $ongoingTasks[$developer->id]['remainingTime'];
                    $timeToComplete = min($remainingTime, $remainingHours);

                    $assignments[] = [
                        'week' => $weeks,
                        'developer' => $developer->name,
                        'task' => $task->title,
                        'hours' => $timeToComplete
                    ];

                    $remainingHours -= $timeToComplete;
                    $remainingTime -= $timeToComplete;

                    if ($remainingTime <= 0) {
                        $tasks = $tasks->where('id', '!=', $task->id);
                        unset($ongoingTasks[$developer->id]);
                    } else {
                        $ongoingTasks[$developer->id]['remainingTime'] = $remainingTime;
                    }
                }

                // Assign new tasks
                while ($remainingHours > 0 && $tasks->isNotEmpty()) {
                    $task = $this->findSuitableTask($tasks, $developer, $remainingHours);
                    if ($task) {
                        $timeToComplete = $this->calculateTimeToComplete($task, $developer);
                        $assignedTime = min($timeToComplete, $remainingHours);

                        $assignments[] = [
                            'week' => $weeks,
                            'developer' => $developer->name,
                            'task' => $task->title,
                            'hours' => $assignedTime
                        ];

                        $remainingHours -= $assignedTime;

                        if ($assignedTime < $timeToComplete) {
                            $ongoingTasks[$developer->id] = [
                                'task' => $task,
                                'remainingTime' => $timeToComplete - $assignedTime
                            ];
                        } else {
                            $tasks = $tasks->where('id', '!=', $task->id);
                        }
                    } else {
                        break;
                    }
                }
            }
        }

        return [
            'weeks' => $weeks,
            'assignments' => $assignments
        ];
    }

    private function findSuitableTask($tasks, $developer, $remainingHours)
    {
        return $tasks->first(function ($task) use ($developer, $remainingHours) {
            $timeToComplete = $this->calculateTimeToComplete($task, $developer);
            return $task->level <= $developer->efficiency;
        });
    }

    private function calculateTimeToComplete($task, $developer)
    {
        $efficiencyRatio = $developer->efficiency / $task->level;
        return $task->time / $efficiencyRatio;
    }
}
