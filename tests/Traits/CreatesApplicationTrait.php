<?php

namespace Feugene\FirebaseNotificationsChannel\Testss\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplicationTrait
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';

        // $app->useStoragePath(...);
        // $app->loadEnvironmentFrom(...);

        $app->make(Kernel::class)->bootstrap();

        // $app->register(...)

        return $app;
    }
}
