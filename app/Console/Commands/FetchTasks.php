<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Factories\TaskProviderFactory;
use App\Models\Task;
use App\Models\Developer;

class FetchTasks extends Command
{
    protected $signature = 'tasks:fetch';
    protected $description = 'Fetch tasks and developers from all providers and store in database';

    public function handle()
    {
        $providers = ['tasks', 'developers'];

        foreach ($providers as $provider) {
            $service = TaskProviderFactory::create($provider);
            $rawData = $service->fetchTasks();
            $normalizedData = $service->normalizeData($rawData);

            if (isset($normalizedData['tasks'])) {
                $this->storeTasks($normalizedData['tasks']);
            }
            if (isset($normalizedData['developers'])) {
                $this->storeDevelopers($normalizedData['developers']);
            }
        }

        $this->info('Tasks and developers fetched and stored successfully.');
    }


    private function storeTasks($tasks)
    {
        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                ['id' => $taskData['id']],
                [
                    'title' => $taskData['title'],
                    'time' => $taskData['time'],
                    'level' => $taskData['level']
                ]
            );
        }
    }

    private function storeDevelopers($developers)
    {
        foreach ($developers as $developerData) {
            Developer::updateOrCreate(
                ['id' => $developerData['id']],
                [
                    'name' => $developerData['name'],
                    'efficiency' => $developerData['level'],
                    'available_hours' => 45 // Default weekly hours
                ]
            );
        }
    }
}