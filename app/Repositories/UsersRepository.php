<?php

namespace App\Repositories;

use App\Models\User;
use EragPermission\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersRepository
{
    public function getUsersPaginate(): LengthAwarePaginator
    {
        return User::with(['roles', 'permissions'])->orderByDesc('id')->paginate(10);
    }

    public function getRole(): LengthAwarePaginator
    {
        return Role::with('permissions')->orderByDesc('id')->paginate(10);
    }

    public function getRoleSelect()
    {
        $role = Role::with('permissions')->orderByDesc('id')->get();

        return $role->map(function ($role) {
            return [
                'name' => $role->name,
                'code' => $role->id,
                'permissions' => $role->permissions,
            ];
        });
    }
}
