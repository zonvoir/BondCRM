<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setup\SocialCredential;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthController extends Controller
{
    public function redirectToOAuth(Request $request): RedirectResponse
    {
        if ($request->type === 'outlook') {
            return Socialite::driver('microsoft')
                ->scopes([
                    'openid',
                    'profile',
                    'offline_access',
                    'https://graph.microsoft.com/Mail.Read',
                    'https://graph.microsoft.com/Mail.Send',
                ])
                ->redirect();
        }

        if ($request->type === 'google') {
            return Socialite::driver('google')
                ->scopes([
                    'https://mail.google.com/',
                    'https://www.googleapis.com/auth/gmail.readonly',
                    'https://www.googleapis.com/auth/gmail.modify',
                    'https://www.googleapis.com/auth/gmail.labels',
                ])
                ->with(['prompt' => 'consent', 'access_type' => 'offline'])
                ->redirect();
        }

        return back();
    }

    public function handleGoogleCallback(Request $request)
    {

        $googleUser = Socialite::driver('google')->user();
        $user = Auth::user();

        SocialCredential::query()->updateOrCreate(
            [
                'provider' => 'gmail',
                'user_id' => $user->id,
            ],
            [
                'provider_user_id' => $googleUser->getId(),
                'credentials' => json_encode([
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                ]),
                'user_id' => $user->id,
                'access_token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
                'token_expires_at' => now()->addSeconds($googleUser->expiresIn),
            ]
        );

        return redirect()->route('employee.setup.index');
    }

    public function handleMicrosoftCallback(Request $request)
    {
        $outlookUser = Socialite::driver('microsoft')->user();
        $user = Auth::user();

        SocialCredential::query()->updateOrCreate(
            [
                'provider' => 'outlook',
                'user_id' => $user->id,
            ],
            [
                'provider_user_id' => $outlookUser->getId(),
                'credentials' => json_encode([
                    'email' => $outlookUser->getEmail(),
                    'name' => $outlookUser->getName(),
                ]),
                'user_id' => $user->id,
                'access_token' => $outlookUser->token,
                'refresh_token' => $outlookUser->refreshToken,
                'token_expires_at' => now()->addSeconds($outlookUser->expiresIn ?? 0),
            ]
        );

        return redirect()->route('employee.setup.index');
    }
}
