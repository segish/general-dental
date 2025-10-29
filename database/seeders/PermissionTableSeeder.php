<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generatePermissions();
        $this->addDashboardPermissions(); // <-- New function for dashboard permissions
    }

    private function generatePermissions()
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return isset($route->getAction()['namespace']) && Str::contains($route->getAction()['namespace'], 'Admin');
        });

        $permissions = $routes->filter(function ($route) {
            return $route->getName() !== null;
        })->flatMap(function ($route) {
            $actions = ['list', 'create', 'edit', 'delete'];
            $resource = explode('.', $route->getName())[0];
            $guard = $route->action['guard'] ?? 'web'; // Default guard is 'web'

            return collect($actions)->map(function ($action) use ($resource, $guard) {
                return ['name' => "$guard-$resource-$action", 'guard_name' => $guard];
            });
        })->unique('name')->values();

        Permission::insert($permissions->toArray());
    }

    private function addDashboardPermissions()
    {
        $dashboardPermissions = [
            'dashboard.view_patient_count',
            'dashboard.view_revenue',
            'dashboard.view_samples_collected',
            'dashboard.view_tests_completed',
            'dashboard.view_appointments',
        ];

        foreach ($dashboardPermissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }
    }
}
