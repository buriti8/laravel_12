<?php

namespace App\Http\Controllers\ProjectControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Export\Export;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $search = $request->has('q') ? $request->get('q') ?? [] : get_last_user_search('users', []);

        set_last_user_search('users', $search);

        $roles = Role::orderBy('name')->get();
        $per_page = module_per_page('users', 20);
        $users = User::search($search)->paginate($per_page);
        $users->appends($search + ['per_page' => $per_page]);

        return view('users.index', [
            'users' => $users,
            'search' => $search,
            'roles' => $roles,
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(User $user)
    {
        return view('users.create', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(['id', 'name'])->except(1),
        ]);
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = new User($request->validated());

            if ($user->save()) {
                $this->saveRoles($user, $request->get('role', []));

                Session::flash('success', __('users.created', ['name' => $user->name]));
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            Session::flash('error', __('users.error', ['name' => $user->name ?? '', 'action' => 'creado']));
        }

        return redirect(route('users.index'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(['id', 'name'])->except(1),
        ]);
    }

    /**
     * @param User $user
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user->fill($request->validated());

            if ($user->save()) {
                $this->saveRoles($user, $request->get('role', []));

                Session::flash('success', __('users.updated', ['name' => $user->name]));
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            Session::flash('error', __('users.error', ['name' => $user->name ?? '', 'action' => 'actualizado']));
        }

        return redirect(route('users.index'));
    }

    /**
     * @param User $user
     * @param array $roles
     */
    private function saveRoles(User $user, array $roles = [])
    {
        $roleNames = Role::whereIn('id', $roles)->pluck('name')->toArray();
        $user->syncRoles($user->is_admin ? User::ADMINISTRADOR : $roleNames);
    }

    /**
     * @param User $user
     */
    public function show(User $user)
    {
        abort(404);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function passwordEdit(User $user)
    {
        return view('users.password.edit', [
            'user' => $user,
        ]);
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function passwordUpdate(User $user, UpdatePasswordRequest $request)
    {
        if ($user->update(['password' => $request->get("password")])) {
            Session::flash('message', __('users.updated', ['name' => $user->name]));
        }

        return redirect(route('users.index'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        Session::flash('message', __('users.deleted', ['name' => $user->name]));
        return redirect('users');
    }

    /**
     * @param User $user
     */
    public function impersonate(User $user)
    {
        Auth::user()->impersonate($user);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function export()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $query = get_last_user_search('users', []);

        $users = User::searchUser($query)
            ->with(['roles', 'typeTransport'])
            ->orderBy('name', 'ASC')
            ->get();

        if ($users->isNotEmpty()) {
            $export = new Export();
            $export->exportUsers($users, 'Usuarios.xlsx');
        } else {
            Session::flash('message_danger', __('base_lang.empty_export'));
            return redirect()->route('users.index');
        }
    }
}
