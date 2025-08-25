<?php

namespace App\Menu;

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

            if (request()->routeIs('employee.setup.*') || Str::contains(url()->current(), 'setup')) {
                return array_values(array_filter(self::employeeMenu(), function ($item) {
                    return $item['name'] === 'Setup';
                }));
            }

            return array_values(
                array_filter(self::employeeMenu(), fn ($item) => $item['name'] !== 'Setup')
            );
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
            [
                'name' => ' Email Black List',
                'permission' => hasPermissions('email-black-view'),
                'href' => route('email.blacklist.index'),
                'icon' => 'flat-color-icons:cancel',
                'active' => $currentRouteName === 'email.blacklist.index',
                'subMenu' => [],
            ],
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

        return [
            [
                'name' => 'Dashboard',
                'permission' => hasPermissions('dashboard-view-employee'),
                'href' => route('employee.dashboard'),
                'icon' => 'vscode-icons:file-type-homeassistant',
                'active' => $currentRouteName === 'employee.dashboard',
                'subMenu' => [],
            ],
            [
                'name' => 'Mails',
                'permission' => hasPermissions('dashboard-view-employee'),
                'icon' => 'material-icon-theme:folder-mail-open',
                'active' => in_array($currentRouteName, ['employee.gmail', 'employee.outlook', 'employee.webmail', 'employee.apple-mail'], true),
                'subMenu' => [
                    [
                        'name' => 'Gmail',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.gmail', 'inbox'),
                        'active' => $currentRouteName === 'employee.gmail',
                    ],
                    [
                        'name' => 'Outlook',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.outlook', 'inbox'),
                        'active' => $currentRouteName === 'employee.outlook',
                    ],
                    [
                        'name' => 'WebMail',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.webmail', 'inbox'),
                        'active' => $currentRouteName === 'employee.webmail',
                    ],
                    [
                        'name' => 'AppMail',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.apple-mail', 'inbox'),
                        'active' => $currentRouteName === 'employee.apple-mail',
                    ],
                ],
            ],

            [
                'name' => 'Setup',
                'permission' => hasPermissions('dashboard-view-employee'),
                'icon' => 'material-icon-theme:folder-config-open',
                'active' => in_array($currentRouteName, ['employee.setup.index', 'employee.imap.settings'], true),
                'subMenu' => [
                    [
                        'name' => 'Mails configure',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.setup.index'),
                        'active' => $currentRouteName === 'employee.setup.index' || $currentRouteName === 'employee.imap.settings',
                    ],

                ],
            ],
        ];
    }
}
