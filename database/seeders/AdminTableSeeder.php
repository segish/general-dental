<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\QueryException;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            // Super Admin
            $superAdmin = Admin::create([
                'f_name' => 'Super',
                'l_name' => 'Admin',
                'phone' => '01759412381',
                'email' => 'super@admin.com',
                'image' => 'avatar/avatar.jpg',
                'department_id' => 1,
                'password' => bcrypt(12345678),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
            $permissions = Permission::where('guard_name', 'admin')->pluck('id', 'id')->all();
            $superAdminRole->syncPermissions($permissions);
            $superAdmin->assignRole([$superAdminRole->id]);

            // Doctor Admin
            $doctorAdmin = Admin::create([
                'f_name' => 'John',
                'l_name' => 'Doe',
                'phone' => '01759412382',
                'email' => 'doctor@admin.com',
                'image' => 'avatar/avatar.jpg',
                'department_id' => 2,
                'password' => bcrypt(12345678),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $doctorRole = Role::firstOrCreate(['name' => 'Doctor', 'guard_name' => 'admin']);
            $doctorPermission = Permission::firstOrCreate(['name' => 'medical_record.add-new', 'guard_name' => 'admin']);

            $doctorRole->givePermissionTo($doctorPermission);
            $doctorAdmin->assignRole([$doctorRole->id]);

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()->withInput()->withErrors(['email' => 'Email already exists']);
            } else {
                throw $e;
            }
        }
    }
}
