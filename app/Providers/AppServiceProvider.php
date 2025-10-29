<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Exception;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // ✅ Fix for Doctrine DBAL not recognizing enum columns
        if (class_exists(Type::class)) {
            try {
                if (!Type::hasType('enum')) {
                    Type::addType('enum', StringType::class);
                }

                $connection = Schema::getConnection();
                $platform = $connection->getDoctrineSchemaManager()->getDatabasePlatform();
                $platform->registerDoctrineTypeMapping('enum', 'string');
            } catch (\Throwable $e) {
                // Ignore during initial migrations or CLI setup
            }
        }

        // ✅ Handle timezone setting safely
        try {
            $timezone = \App\Models\BusinessSetting::where('key', 'time_zone')->first();
            if ($timezone) {
                config(['app.timezone' => $timezone->value]);
                date_default_timezone_set($timezone->value);
            }
        } catch (\Throwable $e) {
            // Ignore errors before DB is migrated
        }

        Paginator::useBootstrap();
    }
}
