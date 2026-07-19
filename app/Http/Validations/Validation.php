<?php

namespace App\Http\Validations;

use Illuminate\Support\Facades\Auth;

abstract class Validation
{
    /**
     * @param $module
     * @return array
     */
    public static function permissionsBase($module)
    {
        $permissions_base = config('modules.base_permissions', []);
        $permissions = [];

        foreach ($permissions_base as $perm => $p) {
            $permissions[] = $perm . '_' . $module;
        }
        return $permissions;
    }

    /**
     * @param $module
     * @param array|null $permissions
     * @return bool
     */
    public static function permissionsUser($module, ?array $permissions = null)
    {
        $user = Auth::user();
        $permissions = Validation::permissionsBase($module);

        if ($user->is_admin || $user->hasAnyPermission($permissions)) {
            return true;
        }
        return false;
    }

    /**
     * @param $parameter
     * @return bool
     */
    public static function validate($parameter)
    {
        switch ($parameter) {
            case 'master':
                return Validation::permissionsUser('lists');
            default:
                return false;
        }
    }

    /**
     * @param $module
     * @return string
     */
    public static function permissionsRoute($module)
    {
        $permissions_base = config('modules.base_permissions', []);
        $permissions = '';

        foreach ($permissions_base as $perm => $p) {
            $permissions = $permissions . $perm . '_' . $module . '|';
        }
        return 'role_or_permission:Administrador|' . $permissions;
    }
}
