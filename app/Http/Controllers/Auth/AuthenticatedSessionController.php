<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Settings\SocialiteSettingsResource;
use App\Repositories\Settings\SettingsRepository;
use App\Services\RoleRedirectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function __construct(protected SettingsRepository $SettingsRepository, protected RoleRedirectService $roleRedirectService) {}

    /**
     * Display the login view.
     */
    public function create(): Response
    {
        $props = [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'socialiteSettings' => [
                'linkedin' => new SocialiteSettingsResource($this->SettingsRepository->getSocialiteSettings('linkedin')),
                'google' => new SocialiteSettingsResource($this->SettingsRepository->getSocialiteSettings('google')),
            ],
        ];

        return Inertia::render('Auth/Login', $props);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return $this->roleRedirectService->redirectToDashboard();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
