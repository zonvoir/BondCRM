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
                'href' => route('dashboard'),
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
                'href' => route('dashboard'),
                'icon' => 'heroicons:home',
                'active' => $currentRouteName === 'dashboard',
                'subMenu' => [],
            ],
            [
                'name' => 'Mails',
                'permission' => hasPermissions('dashboard-view-employee'),
                'icon' => 'heroicons:envelope',
                'active' => in_array($currentRouteName, ['employee.gmail', 'employee.outlook', 'employee.webmail', 'employee.apple-mail'], true),
                'subMenu' => [
                    [
                        'name' => 'Gmail',
                        'icon' =>  'heroicons:envelope-open',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.gmail', 'inbox'),
                        'active' => $currentRouteName === 'employee.gmail',
                    ],
                    [
                        'name' => 'Outlook',
                        'icon' => 'mdi:microsoft-outlook',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.outlook', 'inbox'),
                        'active' => $currentRouteName === 'employee.outlook',
                    ],
                    [
                        'name' => 'WebMail',
                        'icon' => 'mdi:webhook',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.webmail', 'inbox'),
                        'active' => $currentRouteName === 'employee.webmail',
                    ],
                    [
                        'name' => 'AppMail',
                        'icon' => 'mdi:email-multiple',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.apple-mail', 'inbox'),
                        'active' => $currentRouteName === 'employee.apple-mail',
                    ],
                ],
            ],
            [
                'name' => 'Leads',
                'permission' => hasPermissions('dashboard-view-employee'),
                'icon' => 'heroicons:user-group',
                'active' => in_array($currentRouteName, ['employee.lead.index', 'employee.lead.social', 'employee.lead.import', 'employee.lead.details'], true),
                'subMenu' => [
                    [
                        'name' => 'Lead',
                        'icon' =>  'mdi:account-group',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.lead.index'),
                        'active' => $currentRouteName === 'employee.lead.index' || $currentRouteName === 'employee.lead.import' || $currentRouteName === 'employee.lead.details',
                    ],
                ],
            ],
            [
                'name' => 'Black',
                'permission' => hasPermissions('dashboard-view-employee'),
                'icon' => 'heroicons:shield-exclamation',
                'active' => in_array($currentRouteName, ['employee.black.email', 'employee.black.words'], true),
                'subMenu' => [
                    [
                        'name' => 'Email',
                        'icon'=>'heroicons:envelope',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.black.email'),
                        'active' => $currentRouteName === 'employee.black.email',
                    ],
                    [
                        'name' => 'Keyword',
                        'icon' =>  'heroicons:document-text',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.black.word'),
                        'active' => $currentRouteName === 'employee.black.word',
                    ],
                ],
            ],
        ];
    }
}
