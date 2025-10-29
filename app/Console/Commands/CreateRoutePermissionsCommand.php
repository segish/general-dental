<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class CreateRoutePermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a permission routes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding Route and dashboard permissions');
        try {
            $routes = Route::getRoutes()->getRoutes();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        // Handle route-based permissions
        foreach ($routes as $route) {
            if ($route->getName() != '' && $route->getAction()['middleware'][0] == 'web') {
                $routeNameWithoutPrefix = preg_replace('/^admin\./', '', $route->getName(), 1);
                $groupName = strtok($routeNameWithoutPrefix, '.');

                $permission = Permission::where('name', $routeNameWithoutPrefix)->first();

                if (is_null($permission)) {
                    Permission::create([
                        'name' => $routeNameWithoutPrefix,
                        'group' => $groupName,
                        'guard_name' => 'admin',
                    ]);
                }
            }
        }

        // Add custom dashboard permissions
        $dashboardPermissions = [
            'dashboard.view_patient_count',
            'dashboard.view_revenue',
            'dashboard.view_staff_count',
            'dashboard.view_department_count',
            'dashboard.view_pending_tests',
            'dashboard.view_completed_tests',
            'dashboard.view_total_samples_collected',
            'dashboard.view_critical_alerts',
            'dashboard.view_patients_registered_today',
            'dashboard.view_pending_payments',
            'dashboard.view_samples_received_today',
            'dashboard.view_tests_completed_today',
            'dashboard.view_test_result_processed_today',
            'dashboard.view_test_result_approved_today',
            'dashboard.view_pending_test_reports',
            'dashboard.view_pending_sample_collections',
            'dashboard.view_rejected_samples',
            'dashboard.view_critical_samples_tests',
            'dashboard.view_todays_laboratory_requests',
            'dashboard.view_todays_billings_list',
            'dashboard.view_todays_visit_list',
        ];

        foreach ($dashboardPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();

            if (is_null($permission)) {
                Permission::create([
                    'name' => $permissionName,
                    'group' => 'dashboard',
                    'guard_name' => 'admin',
                ]);
            }
        }

        $this->info('Route and dashboard permissions added successfully.');
        $this->info('creating default permissions');

        // Call the DefaultRolesSeeder to create default roles and assign permissions
        $this->call('db:seed', ['--class' => 'DefaultRolesSeeder']);
        $this->call('db:seed', ['--class' => 'AdminTableSeeder']);

        $this->info('Default roles and permissions created successfully.');
    }
}
