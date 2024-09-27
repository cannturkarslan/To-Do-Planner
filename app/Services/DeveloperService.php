<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeveloperService implements ProviderServiceInterface
{
    public function fetchTasks(): array
    {
        $response = Http::get('https://gist.githubusercontent.com/firatozpinar/18cc10a74a98b5381d169ade6d7627d9/raw/f49c19b22412be0a380d39550d3ebd23837b637c/gistfile1.txt');

        $decodedResponse = json_decode($response->body(), true);
        Log::info('Developer API Response: ' . $response->body());

        // Extract task and developer data from the second mock API response
        $task = $decodedResponse['data'] ?? [];
        $developers = $decodedResponse['relations']['developers']['data'] ?? [];

        return [
            'task' => $task,
            'developers' => $developers
        ];
    }
    public function normalizeData(array $data): array
    {
        return $data;
    }
}