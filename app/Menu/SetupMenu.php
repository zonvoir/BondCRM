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
                'permission' => hasPermissions('email-black-view'),
                'icon' => 'material-icon-theme:folder-config-open',
                'active' => in_array($currentRouteName, ['setup.general', 'setup.pwa', 'setup.smtp', 'setup.social', 'setup.chat', 'setup.openai'], true),
                'subMenu' => [
                    [
                        'name' => 'General Settings',
                        'permission' => hasPermissions('email-black-view'),
                        'href' => route('setup.general'),
                        'active' => $currentRouteName === 'setup.general',
                    ],
                    [
                        'name' => 'Pwa Settings',
                        'permission' => hasPermissions('email-black-view'),
                        'href' => route('setup.pwa'),
                        'active' => $currentRouteName === 'setup.pwa',
                    ],
                    [
                        'name' => 'Smtp Settings',
                        'permission' => hasPermissions('email-black-view'),
                        'href' => route('setup.smtp'),
                        'active' => $currentRouteName === 'setup.smtp',
                    ],
                    [
                        'name' => 'Social Settings',
                        'permission' => hasPermissions('email-black-view'),
                        'href' => route('setup.social'),
                        'active' => $currentRouteName === 'setup.social',
                    ],
                    [
                        'name' => 'Chat Settings',
                        'permission' => hasPermissions('email-black-view'),
                        'href' => route('setup.chat'),
                        'active' => $currentRouteName === 'setup.chat',
                    ],
                    [
                        'name' => 'OpenAi Settings',
                        'permission' => hasPermissions('email-black-view'),
                        'href' => route('setup.openai'),
                        'active' => $currentRouteName === 'setup.openai',
                    ],
                ],
            ],
        ];
    }

    public static function userSetupMenu(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [

        ];
    }

    public static function employeeSetupMenu(): array
    {
        $currentRouteName = Route::currentRouteName();

        return [
            [
                'name' => 'Setup',
                'permission' => hasPermissions('dashboard-view-employee'),
                'icon' => 'material-icon-theme:folder-config-open',
                'active' => in_array($currentRouteName, ['employee.setup.index', 'employee.imap.settings', 'employee.smtp'], true),
                'subMenu' => [
                    [
                        'name' => 'Mails configure',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.setup.index'),
                        'active' => $currentRouteName === 'employee.setup.index',
                    ],
                    [
                        'name' => 'IMAP Web Mail',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.imap.settings', 'webmail'),
                        'active' => request()->is('employee/setup/imap/webmail'),
                    ],
                    [
                        'name' => 'IMAP Apple Mail',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.imap.settings', 'applemail'),
                        'active' => request()->is('employee/setup/imap/applemail'),
                    ],
                    [
                        'name' => 'SMTP configure',
                        'permission' => hasPermissions('dashboard-view-employee'),
                        'href' => route('employee.smtp'),
                        'active' => $currentRouteName === 'employee.smtp',
                    ],

                ],
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
            return route('setup.general');
        }

        if (hasRole(RoleEnum::EMPLOYEE->value)) {
            return route('employee.setup.index');
        }

        return null;
    }
}
