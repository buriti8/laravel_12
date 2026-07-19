<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = config('modules.modules', []);
        $permissions = config('modules.base_permissions', []);

        foreach ($modules as $mod => $m) {
            foreach ($permissions as $perm => $p) {
                $name_permission = $perm . '_' . $mod;
                $pp = Permission::where('name', $name_permission)->where('guard_name', 'web')->first();

                if (!$pp) {
                    Permission::updateOrCreate(['name' => $name_permission]);
                }
            }
        }
    }
}
