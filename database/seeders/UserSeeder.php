<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use EragPermission\Models\Permission;
use EragPermission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'role' => RoleEnum::ADMIN->value,
                'name' => RoleEnum::ADMIN->value,
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123'),
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'role' => RoleEnum::EMPLOYEE->value,
                'name' => RoleEnum::EMPLOYEE->value,
                'email' => 'employee@gmail.com',
                'password' => Hash::make('123'),
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'role' => RoleEnum::USER->value,
                'name' => RoleEnum::USER->value,
                'email' => 'user@gmail.com',
                'password' => Hash::make('123'),
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ],
        ];

        $allPermissions = collect(config('permissions'))
            ->flatMap(fn ($group) => collect($group)->flatten())
            ->filter()
            ->unique();

        $userPermissions = $allPermissions->filter(fn ($permission) => str_contains($permission, '-user'))->values();
        $employeePermissions = $allPermissions->filter(fn ($permission) => str_contains($permission, '-employee'))->values();
        $adminPermissions = $allPermissions
            ->reject(fn ($permission) => str_contains($permission, '-employee') || str_contains($permission, '-user'))
            ->values();

        foreach ($users as $userData) {
            $roleName = $userData['role'];
            $role = Role::firstOrCreate(['name' => $roleName]);

            unset($userData['role']);
            $user = User::query()->create($userData);

            $permissionsToAssign = match ($roleName) {
                RoleEnum::EMPLOYEE->value => $employeePermissions,
                RoleEnum::USER->value => $userPermissions,
                default => $adminPermissions,
            };

            foreach ($permissionsToAssign as $permissionName) {
                $permission = Permission::query()->firstOrCreate(['name' => $permissionName]);
                $role->permissions()->syncWithoutDetaching($permission);
            }

            $user->assignRole($roleName);
            $user->givePermissionsTo($permissionsToAssign->toArray());
        }
    }
}
