<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TasksProviderService implements ProviderServiceInterface
{
    public function fetchTasks(): array
    {
        // Fetch the response from the API
        $response = Http::get('https://gist.githubusercontent.com/firatozpinar/8b6ac47e177f07bd99046f873154cef3/raw/b01e456f644370b1365363005c52631e182e66eb/gistfile1.txt');

        $rawResponse = $response->body();
        Log::info('Tasks API Raw Response: ' . $rawResponse);

        // Add missing ] and } if the response is malformed
        if (substr(trim($rawResponse), -1) !== ']') {
            $rawResponse = rtrim($rawResponse, ',') . ']}'; // Ensures the correct closing characters are added
        }

        Log::info('Corrected Response before Decoding: ' . $rawResponse);

        // Decode the fixed response
        $decodedResponse = json_decode($rawResponse, true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON Decode Error: ' . json_last_error_msg());
        }

        Log::info('Fully Decoded Tasks API Response: ' . json_encode($decodedResponse));

        // Return the data field if available
        if (isset($decodedResponse['data'])) {
            Log::info('Decoded Tasks API Response: Data fetched successfully');
            return $decodedResponse['data'];
        } else {
            Log::error('Decoded Tasks API Response: No data field found', ['decodedResponse' => $decodedResponse]);
            return [];
        }
    }
    public function normalizeData(array $data): array
    {
        return $data;
    }
}
