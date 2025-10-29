<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class BranchPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "system.dashboard",
            "pos.index",
            "pos.order-list",
            "orders.list",
        ];

        foreach ($permissions as $permission) {
            // Extracting group part for each iteration
            [$group] = explode('.', $permission, 2);

            $existingPermission = DB::table('permissions')
                ->where('name', $permission)
                ->where('guard_name', 'branch')
                ->first();

            if (!$existingPermission) {
                DB::table('permissions')->insert([
                    'name' => $permission,
                    'group' => $group,
                    'guard_name' => 'branch',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
