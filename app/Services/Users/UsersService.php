<?php

namespace App\Services\Users;

use App\Models\User;
use EragPermission\Models\Permission;
use EragPermission\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UsersService
{
    public function userCreateOrUpdate(array $data): Model|User
    {
        unset($data['password_confirmation']);

        if (! empty($data['id']) && empty($data['password'])) {
            unset($data['password']);
        }

        $user = User::query()->updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );

        $role = Role::with('permissions:id,name')->find($data['roles']['code'] ?? null);

        if ($role) {
            $permissions =

                $role->permissions->pluck('name')->unique()->values()->toArray();
            $user->assignRole($role->name);
            $user->givePermissionsTo($permissions);
        }

        return $user;
    }

    public function deleteUser(User $user): ?bool
    {
        return $user->delete();
    }

    public function roleCreateOrUpdate(array $data)
    {
        return Role::query()->updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }

    public function deleteRole(Role $role): ?bool
    {
        return $role->delete();
    }

    public function getPermissions(): array
    {
        return collect(config('permissions'))
            ->unique()
            ->all();
    }

    public function permissionsAssignRole(array $data)
    {
        $role = Role::query()->find($data['role_id']);
        $role->permissions()->detach();
        foreach (array_unique($data['name']) as $permission) {
            $permissions = Permission::query()->where('name', $permission)->get();
            $role->permissions()->attach($permissions);
        }
    }

    public function userAssignPermission(array $data): Model|Collection|User|null
    {
        $user = User::query()->find($data['user_id']);
        $user?->givePermissionsTo($data['permissions']);

        return $user;
    }
}
