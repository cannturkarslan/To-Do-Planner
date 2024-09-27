<?php

namespace App\Factories;

use App\Services\TasksProviderService;
use App\Services\DeveloperService;
use App\Services\ProviderServiceInterface;

class TaskProviderFactory
{
 
    private static $providers = [
        'tasks' => TasksProviderService::class,
        'developers' => DeveloperService::class,
    ];

    public static function create($provider): ProviderServiceInterface {
        if (!isset(self::$providers[$provider])) {
            throw new \Exception("Provider not supported");
        }

        return new self::$providers[$provider]();
    }
    public static function addProvider($name, $class) {
        self::$providers[$name] = $class;
    }
}
