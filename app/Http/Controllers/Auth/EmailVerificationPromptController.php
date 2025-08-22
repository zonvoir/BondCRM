<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\RoleRedirectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    public function __construct(protected RoleRedirectService $roleRedirectService) {}

    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
                    ? $this->roleRedirectService->redirectToDashboard()

                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
