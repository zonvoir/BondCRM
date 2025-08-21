<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\RoleRequest;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\User\RoleResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\UsersRepository;
use App\Services\Users\UsersService;
use EragPermission\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(protected UsersService $usersService, protected UsersRepository $userRepository) {}

    public function index(Request $request)
    {
        $users = $this->userRepository->getUsersPaginate();

        $props = [
            'users' => UserResource::collection($users),
            'permissions' => $this->usersService->getPermissions(),
            'roles' => $this->userRepository->getRoleSelect(),
        ];

        return Inertia::render('Users/Index', $props);
    }

    public function storeUser(UserRequest $request)
    {
        $this->usersService->userCreateOrUpdate($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function destroyUser(User $user)
    {
        $this->usersService->deleteUser($user);

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }

    public function role(Request $request)
    {
        $roles = $this->userRepository->getRole();

        $props = [
            'roles' => RoleResource::collection($roles),
            'permissions' => $this->usersService->getPermissions(),
        ];

        return Inertia::render('Users/Role', $props);
    }

    public function storeRole(RoleRequest $request)
    {
        $this->usersService->roleCreateOrUpdate($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function destroy(Role $role)
    {
        $this->usersService->deleteRole($role);

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }

    public function permissionsAssignRole(Request $request)
    {
        if (count($request->name) === 0) {
            return back()->with([
                'message' => 'Please select at least one permission.',
                'type' => 'error',
            ]);
        }

        $this->usersService->

        permissionsAssignRole($request->all());

        return back()->with([
            'message' => 'Permissions assign successfully',
            'type' => 'success',
        ]);
    }

    public function permissionsAssignUser(Request $request)
    {
        if (count($request->permissions) === 0) {
            return back()->with([
                'message' => 'Please select at least one permission.',
                'type' => 'error',
            ]);
        }

        $this->usersService->

        userAssignPermission($request->all());

        return back()->with([
            'message' => 'Permissions assign successfully',
            'type' => 'success',
        ]);
    }
}
