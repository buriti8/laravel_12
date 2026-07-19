<?php

namespace App\Http\Controllers\ProjectControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRolePermissionsRequest;
use App\Models\RoleBase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RolesController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $per_page = module_per_page('roles', 20);
        $roles = RoleBase::admin()->OrderBy('name', 'ASC')->paginate($per_page);
        $roles->appends('per_page', $per_page);

        return view('roles.create', [
            'roles' => $roles,
            'roles_base' => [
                mb_strtoupper(User::ADMINISTRADOR),
            ]
        ]);
    }

    /**
     * @param CreateRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateRoleRequest $request)
    {
        $role = new Role($request->validated());
        if ($role->save()) {
            Session::flash('success', __('roles.saved', ['name' => Str::upper($role->name)]));
        }

        return redirect('/roles');
    }

    /**
     * @param \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Role $role)
    {
        try {
            if (!$role->users->count() && $role->delete()) {
                Session::flash('success', __('roles.deleted', ['name' => Str::upper($role->name)]));
            } else {
                Session::flash('error', __('roles.failed', ['name' => Str::upper($role->name)]));
            }
        } catch (\Exception $exception) {
            Log::error('Error deleting role: ' . $exception->getMessage(), [
                'exception' => $exception,
            ]);
        } finally {
            return redirect('roles');
        }
    }

    /**
     * @param Role $role
     * @return mixed
     */
    public function editPermissions(Role $role)
    {
        return view('permissions.create', [
            'role' => $role,
            'modules' => collect(config('modules.modules'))->sort(),
            'fields' => collect(DB::getSchemaBuilder()->getColumnListing('employees'))->sortKeys(),
            'permissions' => config('modules.base_permissions', []),
        ]);
    }

    /**
     * @param Role $role
     * @param UpdateRolePermissionsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function assignPermissions(Role $role, UpdateRolePermissionsRequest $request)
    {
        $role->syncPermissions(collect($request->validated())->keys()->toArray());
        Session::flash('success', 'Los permisos han sido asignados exitosamente.');
        return back();
    }
}
