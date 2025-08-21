<?php

namespace App\Menu;

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;

class SideNav
{
    public static function menu(): array
    {

        if (hasRole(RoleEnum::ADMIN->value)) {
            return self::adminMenu();
        }

        if (hasRole(RoleEnum::USER->value)) {
            return self::userMenu();
        }

        if (hasRole(RoleEnum::EMPLOYEE->value)) {
            return self::employeeMenu();
        }

        return [];
    }

    public static function adminMenu(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [
            [
                'name' => 'Dashboard',
                'permission' => hasPermissions('dashboard-view'),
                'href' => route('dashboard'),
                'icon' => 'vscode-icons:file-type-homeassistant',
                'active' => $currentRouteName === 'dashboard',
                'subMenu' => [],
            ],
            //            [
            //                'name' => 'Bulk Import',
            //                'permission' => hasPermissions('bulk-view'),
            //                'href' => route('email.index'),
            //                'icon' => 'vscode-icons:file-type-excel',
            //                'active' => $currentRouteName === 'email.index',
            //                'subMenu' => [],
            //            ],
            [
                'name' => 'User Management',
                'permission' => hasPermissions('user-view|role-view'),
                'icon' => 'catppuccin:sonar-cloud',
                'active' => in_array($currentRouteName, ['user.role.index', 'user.index'], true),
                'subMenu' => [
                    [
                        'name' => 'Role',
                        'permission' => hasPermissions('role-view'),
                        'href' => route('user.role.index'),
                        'active' => $currentRouteName === 'user.role.index',
                    ],
                    [
                        'name' => 'Users',
                        'permission' => hasPermissions('user-view'),
                        'href' => route('user.index'),
                        'active' => $currentRouteName === 'user.index',
                    ],
                ],
            ],
            //            [
            //                'name' => ' Email Black List',
            //                'permission' => hasPermissions('email-black-view'),
            //                'href' => route('email.blacklist.index'),
            //                'icon' => 'flat-color-icons:cancel',
            //                'active' => $currentRouteName === 'email.blacklist.index',
            //                'subMenu' => [],
            //            ],
        ];
    }

    public static function userMenu(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [
            [
                'name' => 'Dashboard',
                'permission' => hasPermissions('dashboard-view-user'),
                'href' => route('user.dashboard'),
                'icon' => 'vscode-icons:file-type-homeassistant',
                'active' => $currentRouteName === 'dashboard',
                'subMenu' => [],
            ],
        ];
    }

    public static function employeeMenu(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [];
    }
}
