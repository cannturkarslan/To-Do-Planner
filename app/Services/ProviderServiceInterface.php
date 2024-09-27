<?php

namespace App\Services;

interface ProviderServiceInterface
{
    public function fetchTasks(): array;
    public function normalizeData(array $data): array;

}
