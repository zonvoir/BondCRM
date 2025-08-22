<?php

namespace App\Services;

use App\Enums\RoleEnum;
use Illuminate\Http\RedirectResponse;

class RoleRedirectService
{
    public function redirectToDashboard(): RedirectResponse
    {
        if (hasRole(RoleEnum::EMPLOYEE->value)) {
            return redirect()->intended(route('employee.dashboard', absolute: false));
        }

        if (hasRole(RoleEnum::USER->value)) {
            return redirect()->intended(route('user.dashboard', absolute: false));
        }

        if (hasRole(RoleEnum::ADMIN->value)) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        abort(404, 'Dashboard not found for your role.');
    }
}
