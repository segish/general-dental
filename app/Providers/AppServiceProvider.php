<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\BusinessSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\StringType;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        try {
            $connection = Schema::getConnection();
            $platform = $connection->getDoctrineSchemaManager()->getDatabasePlatform();

            // ✅ For newer Doctrine versions
            if (method_exists($platform, 'registerDoctrineTypeMapping')) {
                $platform->registerDoctrineTypeMapping('enum', 'string');
            }

            // ✅ For Doctrine 3+ (uses TypeRegistry)
            if (!Type::hasType('enum')) {
                Type::addType('enum', StringType::class);
            }

            // Keep your timezone logic
            $timezone = \App\Models\BusinessSetting::where('key', 'time_zone')->first();
            if ($timezone) {
                config(['app.timezone' => $timezone->value]);
                date_default_timezone_set($timezone->value);
            }
        } catch (\Throwable $e) {
            // Ignore errors during migration bootstrap
        }

        \Illuminate\Pagination\Paginator::useBootstrap();
    }
}
