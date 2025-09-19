<?php

namespace App\Menu;

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;

class SetupMenu
{
    public static function adminSetupMenu(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [
            [
                'name' => 'Settings',
                'permission' => hasPermissions('dashboard-view'),
                'href' => route('setup.general.index', 'general'),
                'icon' => 'heroicons:cog-6-tooth',
                'active' => $currentRouteName === 'setup.general.index',
                'subMenu' => [],
            ],
            [
                'name' => 'Leads',
                'permission' => hasPermissions('email-black-view'),
                'icon' => 'heroicons:user-group',
                'active' => in_array($currentRouteName, ['setup.source', 'setup.status'], true),
                'subMenu' => [
                    [
                        'name' => 'Sources',
                        'icon' =>  'heroicons:globe-alt',
                        'permission' => hasPermissions('email-black-view'),
                        'href' => route('setup.source'),
                        'active' => $currentRouteName === 'setup.source',
                    ],
                    [
                        'name' => 'Statuses',
                        'icon'=> 'heroicons:inbox-arrow-down',
                        'permission' => hasPermissions('email-black-view'),
                        'href' => route('setup.status'),
                        'active' => $currentRouteName === 'setup.status',
                    ],
                ],
            ],
        ];
    }

    public static function userSetupMenu(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [];
    }

    public static function employeeSetupMenu(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [
            [
                'name' => 'Settings',
                'permission' => hasPermissions('dashboard-view-employee'),
                'href' => route('employee.setup.settings', 'general'),
                'icon' => 'material-icon-theme:settings',
                'active' => $currentRouteName === 'employee.setup.settings',
                'subMenu' => [],
            ],
        ];
    }

    public static function mainMenuSetup(): array
    {
        if (hasRole(RoleEnum::ADMIN->value)) {
            return self::adminSetupMenu();
        }

        if (hasRole(RoleEnum::USER->value)) {
            return self::userSetupMenu();
        }

        if (hasRole(RoleEnum::EMPLOYEE->value)) {
            return self::employeeSetupMenu();
        }

        return [];
    }

    public static function indexRouteName(): ?string
    {
        if (hasRole(RoleEnum::ADMIN->value)) {
            return route('setup.general.index', 'general');
        }

        if (hasRole(RoleEnum::EMPLOYEE->value)) {
            return route('employee.setup.settings', 'general');
        }

        return null;
    }
}
